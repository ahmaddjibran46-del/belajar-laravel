@extends('layouts.kasir')

@section('title', 'Kelola Menu')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-semibold text-ink">Kelola Menu</h1>
    <a href="{{ route('kasir.menu.create') }}" class="inline-flex items-center gap-1.5 bg-coffee-800 hover:bg-coffee-900 text-white text-sm px-4 py-2 rounded-lg transition">
        <x-icon name="plus" class="w-4 h-4" /> Tambah Menu
    </a>
</div>

<div class="bg-white rounded-2xl border border-black/5 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-black/[.02] text-ink/50 text-xs uppercase tracking-wide">
            <tr>
                <th class="text-left px-5 py-3">Menu</th>
                <th class="text-left px-5 py-3">Kategori</th>
                <th class="text-left px-5 py-3">Harga</th>
                <th class="text-left px-5 py-3">Status</th>
                <th class="px-5 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-black/5">
            @forelse ($menus as $menu)
                <tr class="hover:bg-black/[.015]">
                    <td class="px-5 py-3 text-ink font-medium">{{ $menu->nama }}</td>
                    <td class="px-5 py-3 text-ink/60">{{ $menu->kategori->nama ?? '-' }}</td>
                    <td class="px-5 py-3 text-ink/60">Rp{{ number_format($menu->harga, 0, ',', '.') }}</td>
                    <td class="px-5 py-3">
                        <span class="text-xs px-2.5 py-1 rounded-full font-medium {{ $menu->tersedia() ? 'bg-emerald-700 text-white' : 'bg-red-100 text-red-700' }}">
                            {{ $menu->tersedia() ? 'Tersedia' : 'Habis' }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-right whitespace-nowrap">
                        <a href="{{ route('kasir.menu.edit', $menu) }}" class="text-coffee-700 hover:text-coffee-900 font-medium text-xs mr-3">Ubah</a>
                        <form method="POST" action="{{ route('kasir.menu.destroy', $menu) }}" class="inline" onsubmit="return confirm('Hapus menu {{ $menu->nama }}?')">
                            @csrf @method('DELETE')
                            <button class="text-red-500 hover:text-red-600 font-medium text-xs">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-5 py-6 text-center text-ink/40">Belum ada menu.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
