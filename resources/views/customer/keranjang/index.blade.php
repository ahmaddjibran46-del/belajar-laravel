@extends('layouts.customer')

@section('title', 'Keranjang')

@section('content')
<div class="px-5 pt-6 pb-4 flex items-center gap-3">
    <a href="{{ $meja ? route('menu.index', $meja->kode_qr) : url()->previous() }}" class="text-char/60 hover:text-char">
        <x-icon name="arrow-left" class="w-5 h-5" />
    </a>
    <h1 class="font-display text-2xl text-char">Halaman Keranjang</h1>
</div>

@if (session('success'))
    <div class="mx-5 mb-3 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-700 px-4 py-3 text-sm">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="mx-5 mb-3 rounded-xl bg-red-50 border border-red-100 text-red-700 px-4 py-3 text-sm">{{ session('error') }}</div>
@endif

@if (empty($keranjang))
    <div class="px-5 py-16 text-center text-char/50">
        <x-icon name="cart" class="w-10 h-10 mx-auto mb-3 text-char/25" />
        <p class="text-sm">Keranjang kamu masih kosong.</p>
        @if ($meja)
            <a href="{{ route('menu.index', $meja->kode_qr) }}" class="inline-block mt-4 text-chili-700 text-sm font-medium">Lihat Menu &rarr;</a>
        @endif
    </div>
@else
    <div class="px-5 space-y-3">
        @foreach ($keranjang as $key => $item)
            <div class="bg-white rounded-2xl border border-black/5 p-4 flex items-center gap-3">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-char">{{ $item['nama'] }}</p>
                    @if (!empty($item['catatan']))
                        <p class="text-xs text-char/50 mt-0.5">"{{ $item['catatan'] }}"</p>
                    @endif
                    <p class="text-sm text-chili-700 font-semibold mt-1">Rp{{ number_format($item['harga'], 0, ',', '.') }}</p>
                </div>
                <form method="POST" action="{{ route('keranjang.update', $key) }}" class="flex items-center gap-2 shrink-0">
                    @csrf
                    @method('PATCH')
                    <button type="button"
                        onclick="var i=this.nextElementSibling; i.value = Math.max(1, parseInt(i.value) - 1); this.closest('form').submit();"
                        class="w-8 h-8 rounded-full border border-black/10 text-char/60 flex items-center justify-center hover:bg-black/5">
                        <x-icon name="minus" class="w-3.5 h-3.5" />
                    </button>
                    <input type="number" name="qty" value="{{ $item['qty'] }}" min="1" max="20"
                        class="w-8 text-center text-sm border-0 focus:ring-0 p-0" onchange="this.form.submit()">
                    <button type="button"
                        onclick="var i=this.previousElementSibling; i.value = parseInt(i.value) + 1; this.closest('form').submit();"
                        class="w-8 h-8 rounded-full border border-black/10 text-char/60 flex items-center justify-center hover:bg-black/5">
                        <x-icon name="plus" class="w-3.5 h-3.5" />
                    </button>
                </form>
                <form method="POST" action="{{ route('keranjang.hapus', $key) }}">
                    @csrf
                    @method('DELETE')
                    <button class="text-red-400 hover:text-red-500 text-xs font-medium ml-1">Hapus</button>
                </form>
            </div>
        @endforeach
    </div>

    <div class="px-5 mt-4">
        <div class="bg-white rounded-2xl border border-black/5 p-4 flex items-center justify-between mb-4">
            <span class="text-sm text-char/60">Total</span>
            <span class="font-display text-xl text-char">Rp{{ number_format($total, 0, ',', '.') }}</span>
        </div>

        <form method="POST" action="{{ route('keranjang.checkout') }}" class="space-y-3">
            @csrf
            <div>
                <label class="block text-xs text-char/60 mb-1.5">Jenis Pesanan</label>
                <div class="grid grid-cols-2 gap-2">
                    <label class="border border-black/10 rounded-xl px-3 py-2.5 text-sm flex items-center justify-center gap-2 has-[:checked]:border-chili-600 has-[:checked]:bg-chili-50 has-[:checked]:text-chili-700 cursor-pointer transition">
                        <input type="radio" name="tipe" value="dine_in" checked class="accent-chili-600">
                        Makan di Tempat
                    </label>
                    <label class="border border-black/10 rounded-xl px-3 py-2.5 text-sm flex items-center justify-center gap-2 has-[:checked]:border-chili-600 has-[:checked]:bg-chili-50 has-[:checked]:text-chili-700 cursor-pointer transition">
                        <input type="radio" name="tipe" value="take_away" class="accent-chili-600">
                        Bawa Pulang
                    </label>
                </div>
            </div>
            <div>
                <label class="block text-xs text-char/60 mb-1.5">Nama (opsional)</label>
                <input type="text" name="nama_pelanggan" placeholder="Nama kamu, biar mudah dipanggil"
                    class="w-full rounded-xl border border-black/10 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-coffee-500">
            </div>
            <div>
                <label class="block text-xs text-char/60 mb-1.5">Catatan untuk dapur (opsional)</label>
                <input type="text" name="catatan" placeholder="Contoh: pedas level 3, tanpa bawang"
                    class="w-full rounded-xl border border-black/10 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-coffee-500">
            </div>

            <div class="flex items-center justify-between pt-2">
                <span class="font-display text-xl text-char">Total</span>
                <span class="font-display text-xl text-char">Rp{{ number_format($total, 0, ',', '.') }}</span>
            </div>

            <button type="submit" class="w-full bg-gold-600 hover:bg-gold-700 text-white font-semibold rounded-xl py-3.5 text-sm transition">
                Buat Pesanan
            </button>
            <p class="text-center text-xs text-char/40">Kamu akan dapat kode pesanan untuk ditunjukkan ke kasir saat bayar.</p>
        </form>
    </div>
@endif
@endsection
