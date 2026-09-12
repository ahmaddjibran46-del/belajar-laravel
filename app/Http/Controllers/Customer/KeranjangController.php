<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Meja;
use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\PesananItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KeranjangController extends Controller
{
    /**
     * Keranjang disimpan di SESSION browser pelanggan (bukan database),
     * jadi tidak butuh akun sama sekali.
     */
    public function tambah(Request $request)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'qty' => 'required|integer|min:1|max:20',
            'catatan' => 'nullable|string|max:255',
        ]);

        $menu = Menu::findOrFail($request->menu_id);

        if (!$menu->tersedia()) {
            return back()->with('error', 'Maaf, menu ini sedang habis.');
        }

        $keranjang = session('keranjang', []);

        $key = $menu->id . '|' . md5($request->catatan ?? '');

        if (isset($keranjang[$key])) {
            $keranjang[$key]['qty'] += (int) $request->qty;
        } else {
            $keranjang[$key] = [
                'menu_id' => $menu->id,
                'nama' => $menu->nama,
                'harga' => $menu->harga,
                'qty' => (int) $request->qty,
                'catatan' => $request->catatan,
            ];
        }

        session(['keranjang' => $keranjang]);

        return back()->with('success', $menu->nama . ' ditambahkan ke keranjang.');
    }

    public function update(Request $request, string $key)
    {
        $request->validate(['qty' => 'required|integer|min:1|max:20']);

        $keranjang = session('keranjang', []);

        if (isset($keranjang[$key])) {
            $keranjang[$key]['qty'] = (int) $request->qty;
            session(['keranjang' => $keranjang]);
        }

        return back();
    }

    public function hapus(string $key)
    {
        $keranjang = session('keranjang', []);
        unset($keranjang[$key]);
        session(['keranjang' => $keranjang]);

        return back()->with('success', 'Item dihapus dari keranjang.');
    }

    public function index()
    {
        $meja = null;
        if (session('meja_id')) {
            $meja = Meja::find(session('meja_id'));
        }

        $keranjang = session('keranjang', []);
        $total = collect($keranjang)->sum(fn ($item) => $item['harga'] * $item['qty']);

        return view('customer.keranjang.index', compact('meja', 'keranjang', 'total'));
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'tipe' => 'required|in:dine_in,take_away',
            'metode_bayar' => 'required|in:e_wallet,tunai_kasir',
            'nama_pelanggan' => 'nullable|string|max:100',
            'catatan' => 'nullable|string|max:255',
        ]);

        $keranjang = session('keranjang', []);

        if (empty($keranjang)) {
            return redirect()->route('keranjang.index')->with('error', 'Keranjang masih kosong.');
        }

        $meja = session('meja_id') ? Meja::find(session('meja_id')) : null;

        $pesanan = DB::transaction(function () use ($request, $keranjang, $meja) {
            $total = collect($keranjang)->sum(fn ($item) => $item['harga'] * $item['qty']);

            $pesanan = Pesanan::create([
                'meja_id' => $meja?->id,
                'nomor_meja_snapshot' => $meja?->nomor_meja,
                'tipe' => $request->tipe,
                'metode_bayar' => $request->metode_bayar,
                'nama_pelanggan' => $request->nama_pelanggan,
                'catatan' => $request->catatan,
                'status' => 'menunggu_pembayaran',
                'total_harga' => $total,
            ]);

            foreach ($keranjang as $item) {
                PesananItem::create([
                    'pesanan_id' => $pesanan->id,
                    'menu_id' => $item['menu_id'],
                    'nama_menu' => $item['nama'],
                    'harga_satuan' => $item['harga'],
                    'qty' => $item['qty'],
                    'catatan' => $item['catatan'] ?? null,
                    'subtotal' => $item['harga'] * $item['qty'],
                ]);
            }

            return $pesanan;
        });

        // Kosongkan keranjang setelah checkout berhasil.
        session()->forget('keranjang');

        return redirect()->route('pesanan.status', $pesanan->kode_pesanan);
    }
}