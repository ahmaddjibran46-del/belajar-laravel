<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;

class DashboardController extends Controller
{
    public function index()
    {
        $menungguBayar = Pesanan::with('items')
            ->where('status', 'menunggu_pembayaran')
            ->where('metode_bayar', 'tunai_kasir')
            ->orderBy('created_at')
            ->get();

        // Sudah dibayar, sedang disiapkan dapur.
        $diproses = Pesanan::with('items')
            ->whereIn('status', ['dibayar', 'diproses'])
            ->orderBy('created_at')
            ->get();

        // Siap diambil / diantar ke meja.
        $siap = Pesanan::with('items')
            ->where('status', 'siap')
            ->orderBy('created_at')
            ->get();

        return view('kasir.dashboard.index', compact('menungguBayar', 'diproses', 'siap'));
    }
}