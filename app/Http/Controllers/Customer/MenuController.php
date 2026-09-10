<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Meja;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     * Halaman ini yang dibuka saat pelanggan SCAN QR CODE di meja.
     * Tidak perlu login/register sama sekali.
     */
    public function index(Request $request, string $kode_qr)
    {
        $meja = Meja::where('kode_qr', $kode_qr)->firstOrFail();

        // Simpan meja aktif pelanggan ini di session, dipakai saat checkout nanti.
        session(['meja_id' => $meja->id, 'kode_qr' => $meja->kode_qr]);

        $kategoris = Kategori::with(['menus' => function ($q) {
            $q->orderBy('nama');
        }])->orderBy('urutan')->orderBy('nama')->get();

        $keranjang = session('keranjang', []);

        return view('customer.menu.index', compact('meja', 'kategoris', 'keranjang'));
    }
}
