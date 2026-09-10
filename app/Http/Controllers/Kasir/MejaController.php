<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Meja;
use Illuminate\Http\Request;

class MejaController extends Controller
{
    public function index()
    {
        $mejas = Meja::orderBy('nomor_meja')->get();

        return view('kasir.meja.index', compact('mejas'));
    }

    public function create()
    {
        return view('kasir.meja.form', ['meja' => new Meja()]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomor_meja' => 'required|string|max:20',
        ]);

        Meja::create(['nomor_meja' => $request->nomor_meja]);

        return redirect()->route('kasir.meja.index')->with('success', 'Meja berhasil ditambahkan.');
    }

    public function edit(Meja $meja)
    {
        return view('kasir.meja.form', compact('meja'));
    }

    public function update(Request $request, Meja $meja)
    {
        $request->validate([
            'nomor_meja' => 'required|string|max:20',
        ]);

        $meja->update(['nomor_meja' => $request->nomor_meja]);

        return redirect()->route('kasir.meja.index')->with('success', 'Meja berhasil diperbarui.');
    }

    public function destroy(Meja $meja)
    {
        $meja->delete();

        return back()->with('success', 'Meja berhasil dihapus.');
    }
}
