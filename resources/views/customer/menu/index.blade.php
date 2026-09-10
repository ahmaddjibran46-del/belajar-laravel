@extends('layouts.customer')

@section('title', 'Menu - Meja ' . $meja->nomor_meja)

@section('content')
<div class="bg-coffee-800 text-white px-5 pt-6 pb-7 rounded-b-3xl flex items-center justify-between gap-3">
    <div>
        <p class="text-xs uppercase tracking-wide text-white/60">Meja</p>
        <h1 class="font-display text-3xl mt-0.5">{{ $meja->nomor_meja }}</h1>
    </div>
    <x-brand-logo variant="white" size="sm" />
</div>
<p class="text-sm text-char/50 px-5 mt-3">Pilih menu, lalu bayar langsung di kasir.</p>

@if (session('success'))
    <div class="mx-5 mt-3 mb-2 rounded-xl bg-white shadow-md border border-emerald-100 text-emerald-700 px-4 py-3 text-sm">
        {{ session('success') }}
    </div>
@endif
@if (session('error'))
    <div class="mx-5 mt-3 mb-2 rounded-xl bg-white shadow-md border border-red-100 text-red-700 px-4 py-3 text-sm">
        {{ session('error') }}
    </div>
@endif

<div class="px-5 mt-6 space-y-8">
    @forelse ($kategoris as $kategori)
        @if ($kategori->menus->count())
            <section>
                <h2 class="font-display text-lg text-char mb-3">{{ $kategori->nama }}</h2>
                <div class="grid grid-cols-2 gap-3">
                    @foreach ($kategori->menus as $menu)
                        <div class="relative bg-white rounded-2xl border border-black/5 overflow-hidden {{ !$menu->tersedia() ? 'opacity-50' : '' }}">
                            <div class="aspect-square bg-coffee-50 flex items-center justify-center overflow-hidden">
                                @if ($menu->gambar_url)
                                    <img src="{{ $menu->gambar_url }}" class="w-full h-full object-cover" alt="{{ $menu->nama }}">
                                @else
                                    <span class="text-coffee-300 text-3xl">🍜</span>
                                @endif
                            </div>
                            <div class="p-3">
                                <p class="font-medium text-char text-sm leading-snug">{{ $menu->nama }}</p>
                                <p class="text-sm text-chili-700 font-semibold mt-1">Rp{{ number_format($menu->harga, 0, ',', '.') }}</p>
                            </div>
                            <div class="absolute top-2 right-2">
                                @if ($menu->tersedia())
                                    <form method="POST" action="{{ route('keranjang.tambah') }}">
                                        @csrf
                                        <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                                        <input type="hidden" name="qty" value="1">
                                        <button type="submit" class="w-8 h-8 rounded-full bg-gold-600 text-white shadow-sm flex items-center justify-center hover:bg-gold-700 transition">
                                            <x-icon name="plus" class="w-4 h-4" />
                                        </button>
                                    </form>
                                @else
                                    <span class="text-[10px] bg-black/40 text-white px-2 py-1 rounded-full font-medium">Habis</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif
    @empty
        <p class="text-center text-char/50 text-sm py-16">Menu belum tersedia.</p>
    @endforelse
</div>

@php
    $jumlahItem = collect($keranjang)->sum('qty');
    $totalKeranjang = collect($keranjang)->sum(fn($i) => $i['harga'] * $i['qty']);
@endphp

@if ($jumlahItem > 0)
    <a href="{{ route('keranjang.index') }}"
       class="fixed bottom-0 left-0 right-0 max-w-md mx-auto flex items-center justify-between bg-coffee-900 text-white px-5 py-4 rounded-t-2xl shadow-lg">
        <span class="text-sm flex items-center gap-2">
            <x-icon name="cart" class="w-4 h-4" />
            <span class="bg-gold-600 rounded-full px-2 py-0.5 text-xs font-semibold">{{ $jumlahItem }}</span>
            Lihat Keranjang
        </span>
        <span class="font-semibold text-sm">Rp{{ number_format($totalKeranjang, 0, ',', '.') }}</span>
    </a>
@endif
@endsection
