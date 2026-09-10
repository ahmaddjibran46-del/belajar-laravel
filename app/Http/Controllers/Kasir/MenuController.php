<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with('kategori')->orderBy('nama')->get();

        return view('kasir.menu.index', compact('menus'));
    }

    public function create()
    {
        $kategoris = Kategori::orderBy('nama')->get();

        return view('kasir.menu.form', ['menu' => new Menu(), 'kategoris' => $kategoris]);
    }

    public function store(Request $request)
    {
        $data = $this->validasi($request);

        if ($request->hasFile('gambar') && $request->file('gambar')->isValid()) {
            $data['gambar'] = $request->file('gambar')->store('menu', 'public');
        }

        Menu::create($data);

        return redirect()
            ->route('kasir.menu.index')
            ->with('success', 'Menu berhasil ditambahkan.');
    }

    public function edit(Menu $menu)
    {
        $kategoris = Kategori::orderBy('nama')->get();

        return view('kasir.menu.form', compact('menu', 'kategoris'));
    }

    public function update(Request $request, Menu $menu)
    {
        $data = $this->validasi($request);

        if ($request->hasFile('gambar') && $request->file('gambar')->isValid()) {
            $data['gambar'] = $request->file('gambar')->store('menu', 'public');
        }

        $menu->update($data);

        return redirect()
            ->route('kasir.menu.index')
            ->with('success', 'Menu berhasil diperbarui.');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();

        return back()->with('success', 'Menu berhasil dihapus.');
    }

    private function validasi(Request $request): array
    {
        return $request->validate([
            'kategori_id' => 'required|exists:kategoris,id',
            'nama' => 'required|string|max:150',
            'deskripsi' => 'nullable|string|max:500',
            'harga' => 'required|integer|min:0',
            'status' => 'required|in:tersedia,habis',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);
    }
}
