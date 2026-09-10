<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::withCount('menus')->orderBy('urutan')->orderBy('nama')->get();

        return view('kasir.kategori.index', compact('kategoris'));
    }

    public function create()
    {
        return view('kasir.kategori.form', ['kategori' => new Kategori()]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'urutan' => 'nullable|integer',
        ]);

        Kategori::create($request->only('nama', 'urutan'));

        return redirect()->route('kasir.kategori.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Kategori $kategori)
    {
        return view('kasir.kategori.form', compact('kategori'));
    }

    public function update(Request $request, Kategori $kategori)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'urutan' => 'nullable|integer',
        ]);

        $kategori->update($request->only('nama', 'urutan'));

        return redirect()->route('kasir.kategori.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Kategori $kategori)
    {
        $kategori->delete();

        return back()->with('success', 'Kategori berhasil dihapus.');
    }
}
