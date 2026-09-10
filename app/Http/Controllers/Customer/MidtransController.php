<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Midtrans\Config;
use Midtrans\Notification;
use Midtrans\Snap;
use Midtrans\Transaction;

class MidtransController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    /**
     * Buat transaksi baru di Midtrans, lalu tampilkan halaman
     * yang otomatis membuka jendela pembayaran (Snap popup).
     */
    public function bayar(string $kode_pesanan)
    {
        $pesanan = Pesanan::with('items')->where('kode_pesanan', $kode_pesanan)->firstOrFail();

        if ($pesanan->status !== 'menunggu_pembayaran') {
            return redirect()->route('pesanan.status', $pesanan->kode_pesanan);
        }

        // order_id Midtrans harus unik -- tambahkan timestamp supaya tidak
        // bentrok kalau pelanggan buka ulang halaman bayar ini berkali-kali.
        $orderId = $pesanan->kode_pesanan . '-' . time();

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $pesanan->total_harga,
            ],
            'customer_details' => [
                'first_name' => $pesanan->nama_pelanggan ?: 'Pelanggan',
            ],
            'item_details' => $pesanan->items->map(function ($item) {
                return [
                    'id' => (string) ($item->menu_id ?? $item->id),
                    'price' => $item->harga_satuan,
                    'quantity' => $item->qty,
                    'name' => substr($item->nama_menu, 0, 50),
                ];
            })->toArray(),
            // Batasi metode bayar ke QRIS/e-wallet saja, sesuai konsep "bayar dari HP di meja".
            'enabled_payments' => ['qris', 'gopay', 'shopeepay'],
        ];

        $snapToken = Snap::getSnapToken($params);

        // PENTING: simpan order_id ini ke DATABASE (bukan session lagi),
        // supaya tidak hilang walau session browser bermasalah/expired.
        $pesanan->update(['midtrans_order_id' => $orderId]);

        return view('customer.pesanan.midtrans', compact('pesanan', 'snapToken'));
    }

    /**
     * Dipanggil lewat AJAX setelah Snap popup ditutup/selesai, ATAU lewat tombol
     * "Sudah Bayar? Cek Ulang Status" yang bisa diklik kapan saja oleh pelanggan.
     * Sengaja cek LANGSUNG ke server Midtrans (bukan percaya begitu saja
     * data dari browser pelanggan), supaya status yang tersimpan akurat.
     */
    public function cekStatus(string $kode_pesanan)
    {
        $pesanan = Pesanan::where('kode_pesanan', $kode_pesanan)->firstOrFail();

        if ($pesanan->midtrans_order_id) {
            try {
                $status = Transaction::status($pesanan->midtrans_order_id);
                $this->terapkanStatus($pesanan, $status->transaction_status ?? null, $status->fraud_status ?? null);
            } catch (\Exception $e) {
                // Transaksi belum tercatat di Midtrans (mis. popup ditutup sebelum bayar) -> biarkan saja.
            }
        }

        $pesanan->refresh();

        return response()->json(['status' => $pesanan->status, 'label' => $pesanan->labelStatus()]);
    }

    /**
     * Endpoint notifikasi webhook Midtrans.
     * CATATAN: ini hanya akan benar-benar dipakai kalau web sudah online (ada domain publik) --
     * Midtrans tidak bisa mengirim notifikasi ke alamat localhost/IP lokal.
     * Untuk testing lokal, cukup andalkan cekStatus() di atas / tombol cek ulang manual.
     */
    public function notifikasi()
    {
        $notif = new Notification();

        $kodePesanan = preg_replace('/-\d+$/', '', $notif->order_id);
        $pesanan = Pesanan::where('kode_pesanan', $kodePesanan)->first();

        if (!$pesanan) {
            return response()->json(['message' => 'Pesanan tidak ditemukan'], 404);
        }

        $this->terapkanStatus($pesanan, $notif->transaction_status, $notif->fraud_status);

        return response()->json(['message' => 'OK']);
    }

    private function terapkanStatus(Pesanan $pesanan, ?string $transactionStatus, ?string $fraudStatus): void
    {
        if (in_array($transactionStatus, ['capture', 'settlement']) && $fraudStatus !== 'deny') {
            if ($pesanan->status === 'menunggu_pembayaran') {
                $pesanan->update([
                    'status' => 'diproses',
                    'dibayar_pada' => now(),
                ]);

                if ($pesanan->meja_id) {
                    $pesanan->meja->update(['status' => 'terisi']);
                }
            }
        } elseif (in_array($transactionStatus, ['expire', 'cancel', 'deny'])) {
            $pesanan->update(['status' => 'dibatalkan']);
        }
        // 'pending' -> biarkan tetap menunggu_pembayaran, pelanggan belum selesai bayar.
    }
}
