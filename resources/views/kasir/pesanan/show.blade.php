@extends('layouts.kasir')

@section('title', 'Pesanan ' . $pesanan->kode_pesanan)

@section('content')
<a href="{{ route('kasir.dashboard') }}" class="inline-flex items-center gap-1.5 text-sm text-ink/50 hover:text-ink">
    <x-icon name="arrow-left" class="w-4 h-4" /> Kembali ke antrian
</a>

<div class="mt-4 grid grid-cols-3 gap-6">
    <div class="col-span-2 bg-white rounded-2xl border border-black/5 p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <p class="font-mono text-xl text-ink">{{ $pesanan->kode_pesanan }}</p>
                <p class="text-sm text-ink/50 mt-1">
                    {{ $pesanan->tipe === 'dine_in' ? 'Makan di Tempat &middot; Meja ' . ($pesanan->nomor_meja_snapshot ?? '-') : 'Bawa Pulang' }}
                    @if ($pesanan->nama_pelanggan) &middot; {{ $pesanan->nama_pelanggan }} @endif
                </p>
            </div>
            <span class="text-xs px-3 py-1 rounded-full font-medium {{ $pesanan->warnaStatus() }}">{{ $pesanan->labelStatus() }}</span>
        </div>

        @if ($pesanan->catatan)
            <div class="bg-amber-50 border border-amber-100 text-amber-800 text-sm rounded-lg px-3 py-2 mb-4">
                Catatan: {{ $pesanan->catatan }}
            </div>
        @endif

        <div class="divide-y divide-black/5">
            @foreach ($pesanan->items as $item)
                <div class="py-3 flex justify-between text-sm">
                    <div>
                        <p class="text-ink">{{ $item->qty }}x {{ $item->nama_menu }}</p>
                        @if ($item->catatan)
                            <p class="text-xs text-ink/40">"{{ $item->catatan }}"</p>
                        @endif
                    </div>
                    <p class="text-ink/70">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</p>
                </div>
            @endforeach
        </div>

        <div class="pt-3 mt-2 border-t border-black/10 flex justify-between font-semibold text-ink text-lg">
            <span>Total</span>
            <span>Rp{{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-black/5 p-6 h-fit">
        <p class="text-sm font-medium text-ink mb-4">Aksi Kasir</p>

        @if ($pesanan->status === 'menunggu_pembayaran')
            <form method="POST" action="{{ route('kasir.pesanan.bayar', $pesanan) }}">
                @csrf
                <button class="w-full bg-emerald-700 hover:bg-emerald-800 text-white text-sm font-medium rounded-lg py-2.5 transition">
                    Tandai Sudah Dibayar
                </button>
            </form>
            <p class="text-xs text-ink/40 mt-2">Pastikan sudah menerima pembayaran (tunai/QRIS) sebelum konfirmasi.</p>
        @endif

        @if (in_array($pesanan->status, ['dibayar', 'diproses']))
            <form method="POST" action="{{ route('kasir.pesanan.status', $pesanan) }}">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="siap">
                <button class="w-full bg-coffee-700 hover:bg-coffee-800 text-white text-sm font-medium rounded-lg py-2.5 transition">
                    Tandai Siap Diambil/Diantar
                </button>
            </form>
        @endif

        @if ($pesanan->status === 'siap')
            <form method="POST" action="{{ route('kasir.pesanan.status', $pesanan) }}">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="selesai">
                <button class="w-full bg-ink hover:bg-ink/90 text-white text-sm font-medium rounded-lg py-2.5 transition">
                    Tandai Selesai
                </button>
            </form>
        @endif

        @if (!in_array($pesanan->status, ['selesai', 'dibatalkan']))
            <form method="POST" action="{{ route('kasir.pesanan.status', $pesanan) }}" class="mt-2">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="dibatalkan">
                <button class="w-full border border-red-200 text-red-600 hover:bg-red-50 text-sm font-medium rounded-lg py-2.5 transition">
                    Batalkan Pesanan
                </button>
            </form>
        @endif

        @if ($pesanan->kasir)
            <p class="text-xs text-ink/40 mt-4">Diproses oleh {{ $pesanan->kasir->name }}</p>
        @endif
    </div>
</div>
@endsection
