@extends('layouts.kasir')

@section('title', 'Kategori Menu')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-semibold text-ink">Halaman Kategori</h1>
    <a href="{{ route('kasir.kategori.create') }}" class="inline-flex items-center gap-1.5 bg-coffee-800 hover:bg-coffee-900 text-white text-sm px-4 py-2 rounded-lg transition">
        <x-icon name="plus" class="w-4 h-4" /> Tambah Kategori
    </a>
</div>

<div class="bg-white rounded-2xl border border-black/5 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-black/[.02] text-ink/50 text-xs uppercase tracking-wide">
            <tr>
                <th class="text-left px-5 py-3">Nama</th>
                <th class="text-left px-5 py-3">Urutan</th>
                <th class="text-left px-5 py-3">Jumlah Menu</th>
                <th class="px-5 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-black/5">
            @forelse ($kategoris as $i => $kategori)
                <tr class="hover:bg-black/[.015]">
                    <td class="px-5 py-3 text-ink">
                        <span class="text-ink/40 mr-1.5">{{ $i + 1 }}.</span>
                        <span class="font-medium">{{ $kategori->nama }}</span>
                    </td>
                    <td class="px-5 py-3 text-ink/60">{{ $kategori->urutan }}</td>
                    <td class="px-5 py-3 text-ink/60">{{ $kategori->menus_count }}</td>
                    <td class="px-5 py-3 text-right whitespace-nowrap">
                        <a href="{{ route('kasir.kategori.edit', $kategori) }}" class="text-coffee-700 hover:text-coffee-900 font-medium text-xs mr-3">Ubah</a>
                        <form method="POST" action="{{ route('kasir.kategori.destroy', $kategori) }}" class="inline" onsubmit="return confirm('Hapus kategori {{ $kategori->nama }}?')">
                            @csrf @method('DELETE')
                            <button class="text-red-500 hover:text-red-600 font-medium text-xs">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="px-5 py-6 text-center text-ink/40">Belum ada kategori.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
