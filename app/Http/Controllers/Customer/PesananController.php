<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;

class PesananController extends Controller
{
    /**
     * Halaman ini menampilkan kode/QR pesanan untuk ditunjukkan ke kasir,
     * dan status pesanan (menunggu bayar -> diproses -> siap -> selesai).
     */
    public function status(string $kode_pesanan)
    {
        $pesanan = Pesanan::with('items')->where('kode_pesanan', $kode_pesanan)->firstOrFail();

        return view('customer.pesanan.status', compact('pesanan'));
    }

    /**
     * Endpoint JSON kecil untuk auto-refresh status di halaman pelanggan.
     */
    public function cekStatus(string $kode_pesanan)
    {
        $pesanan = Pesanan::where('kode_pesanan', $kode_pesanan)->firstOrFail();

        return response()->json([
            'status' => $pesanan->status,
            'label' => $pesanan->labelStatus(),
        ]);
    }
}
