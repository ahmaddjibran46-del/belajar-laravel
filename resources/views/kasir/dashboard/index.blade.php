@extends('layouts.kasir')

@section('title', 'Antrian Pesanan')

@section('content')
<div class="flex items-center justify-between mb-6 gap-4">
    <div>
        <h1 class="text-2xl font-semibold text-ink">Antrian Pesanan</h1>
        <p class="text-sm text-ink/50 mt-0.5">Halaman ini auto-refresh setiap 10 detik.</p>
    </div>
    <form method="POST" action="{{ route('kasir.pesanan.cari') }}" class="flex gap-2 shrink-0">
        @csrf
        <div class="relative">
            <x-icon name="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-ink/35" />
            <input type="text" name="kode" placeholder="Cari kode pesanan (mis. GC-260902-A1B2)"
                   class="rounded-lg border border-black/10 pl-9 pr-3 py-2 text-sm w-72 focus:outline-none focus:ring-2 focus:ring-coffee-500 bg-white">
        </div>
        <button class="bg-coffee-800 hover:bg-coffee-900 text-white text-sm px-4 py-2 rounded-lg transition">
            <x-icon name="search" class="w-4 h-4" />
        </button>
    </form>
</div>

<div class="grid grid-cols-3 gap-5">
    <div>
        <h2 class="text-sm font-semibold text-ink/70 uppercase tracking-wide mb-3">Menunggu Bayar &middot; {{ $menungguBayar->count() }}</h2>
        <div class="space-y-3">
            @forelse ($menungguBayar as $p)
                <a href="{{ route('kasir.pesanan.show', $p) }}" class="block bg-white rounded-xl border border-black/5 p-4 hover:border-coffee-300 hover:shadow-sm transition">
                    <div class="flex items-center justify-between">
                        <span class="font-mono text-sm text-ink">{{ $p->kode_pesanan }}</span>
                        <span class="text-xs px-2 py-0.5 rounded-full {{ $p->warnaStatus() }}">{{ $p->labelStatus() }}</span>
                    </div>
                    <p class="text-xs text-ink/50 mt-1">
                        {{ $p->tipe === 'dine_in' ? 'Meja ' . ($p->nomor_meja_snapshot ?? '-') : 'Bawa Pulang' }}
                        &middot; {{ $p->items->sum('qty') }} item
                    </p>
                    <p class="text-sm font-semibold text-chili-700 mt-1">Rp{{ number_format($p->total_harga, 0, ',', '.') }}</p>
                </a>
            @empty
                <p class="text-sm text-ink/40">Tidak ada pesanan menunggu bayar.</p>
            @endforelse
        </div>
    </div>

    <div>
        <h2 class="text-sm font-semibold text-ink/70 uppercase tracking-wide mb-3">Diproses Dapur &middot; {{ $diproses->count() }}</h2>
        <div class="space-y-3">
            @forelse ($diproses as $p)
                <a href="{{ route('kasir.pesanan.show', $p) }}" class="block bg-white rounded-xl border border-black/5 p-4 hover:border-coffee-300 hover:shadow-sm transition">
                    <div class="flex items-center justify-between">
                        <span class="font-mono text-sm text-ink">{{ $p->kode_pesanan }}</span>
                        <span class="text-xs px-2 py-0.5 rounded-full {{ $p->warnaStatus() }}">{{ $p->labelStatus() }}</span>
                    </div>
                    <p class="text-xs text-ink/50 mt-1">
                        {{ $p->tipe === 'dine_in' ? 'Meja ' . ($p->nomor_meja_snapshot ?? '-') : 'Bawa Pulang' }}
                        &middot; {{ $p->items->sum('qty') }} item
                    </p>
                </a>
            @empty
                <p class="text-sm text-ink/40">Tidak ada pesanan diproses.</p>
            @endforelse
        </div>
    </div>

    <div>
        <h2 class="text-sm font-semibold text-ink/70 uppercase tracking-wide mb-3">Siap Diambil &middot; {{ $siap->count() }}</h2>
        <div class="space-y-3">
            @forelse ($siap as $p)
                <a href="{{ route('kasir.pesanan.show', $p) }}" class="block bg-white rounded-xl border border-black/5 p-4 hover:border-coffee-300 hover:shadow-sm transition">
                    <div class="flex items-center justify-between">
                        <span class="font-mono text-sm text-ink">{{ $p->kode_pesanan }}</span>
                        <span class="text-xs px-2 py-0.5 rounded-full {{ $p->warnaStatus() }}">{{ $p->labelStatus() }}</span>
                    </div>
                    <p class="text-xs text-ink/50 mt-1">
                        {{ $p->tipe === 'dine_in' ? 'Meja ' . ($p->nomor_meja_snapshot ?? '-') : 'Bawa Pulang' }}
                    </p>
                </a>
            @empty
                <p class="text-sm text-ink/40">Belum ada pesanan siap.</p>
            @endforelse
        </div>
    </div>
</div>

<script>
    setTimeout(() => location.reload(), 10000);
</script>
@endsection
