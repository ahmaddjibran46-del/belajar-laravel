<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PesananController extends Controller
{
    /**
     * Pencarian pesanan lewat kode pesanan (dari layar/QR yang ditunjukkan pelanggan).
     */
    public function cari(Request $request)
    {
        $request->validate(['kode' => 'required|string']);

        $pesanan = Pesanan::where('kode_pesanan', strtoupper(trim($request->kode)))->first();

        if (!$pesanan) {
            return back()->with('error', 'Pesanan dengan kode tersebut tidak ditemukan.');
        }

        return redirect()->route('kasir.pesanan.show', $pesanan);
    }

    public function show(Pesanan $pesanan)
    {
        $pesanan->load('items', 'meja', 'kasir');

        return view('kasir.pesanan.show', compact('pesanan'));
    }

    /**
     * Kasir menandai pesanan sudah dibayar (uang tunai / QRIS fisik di kasir).
     */
    public function bayar(Pesanan $pesanan)
    {
        if ($pesanan->status !== 'menunggu_pembayaran') {
            return back()->with('error', 'Pesanan ini sudah diproses sebelumnya.');
        }

        $pesanan->update([
            'status' => 'diproses',
            'kasir_id' => Auth::id(),
            'dibayar_pada' => now(),
        ]);

        if ($pesanan->meja_id) {
            $pesanan->meja->update(['status' => 'terisi']);
        }

        return back()->with('success', 'Pembayaran dikonfirmasi. Pesanan diteruskan ke dapur.');
    }

    /**
     * Update status lanjutan: siap / selesai / dibatalkan.
     */
    public function updateStatus(Request $request, Pesanan $pesanan)
    {
        $request->validate([
            'status' => 'required|in:diproses,siap,selesai,dibatalkan',
        ]);

        $pesanan->update(['status' => $request->status]);

        if (in_array($request->status, ['selesai', 'dibatalkan']) && $pesanan->meja_id) {
            $pesanan->meja->update(['status' => 'kosong']);
        }

        return back()->with('success', 'Status pesanan diperbarui menjadi "' . $pesanan->labelStatus() . '".');
    }
}
