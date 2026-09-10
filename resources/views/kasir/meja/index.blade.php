@extends('layouts.kasir')

@section('title', 'Meja & QR Code')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-semibold text-ink">Meja &amp; QR Code</h1>
        <p class="text-sm text-ink/50 mt-0.5">Cetak &amp; tempel QR ini di tiap meja agar pelanggan bisa langsung memesan.</p>
    </div>
    <a href="{{ route('kasir.meja.create') }}" class="inline-flex items-center gap-1.5 bg-coffee-800 hover:bg-coffee-900 text-white text-sm px-4 py-2 rounded-lg transition shrink-0">
        <x-icon name="plus" class="w-4 h-4" /> Tambah Meja
    </a>
</div>

<div class="grid grid-cols-4 gap-4">
    @forelse ($mejas as $meja)
        @php $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=240x240&data=" . urlencode($meja->url_pesan); @endphp
        <div class="bg-white rounded-2xl border border-black/5 p-5 text-center">
            <p class="text-[11px] text-ink/40 uppercase tracking-wide">Meja</p>
            <p class="text-2xl font-semibold text-ink mb-3">{{ $meja->nomor_meja }}</p>
            <img src="{{ $qrUrl }}" alt="QR Meja {{ $meja->nomor_meja }}" class="mx-auto rounded-lg border border-black/5">
            <p class="text-[11px] text-ink/40 mt-2 break-all">{{ $meja->url_pesan }}</p>

            <a href="{{ $qrUrl }}" download="meja-{{ $meja->nomor_meja }}-qr.png" target="_blank"
               class="mt-3 w-full inline-flex items-center justify-center gap-1.5 border border-black/10 hover:border-coffee-400 hover:text-coffee-700 text-ink/70 text-xs font-medium rounded-full py-2 transition">
                <x-icon name="download" class="w-3.5 h-3.5" /> Download
            </a>

            <div class="flex items-center justify-center gap-3 mt-3 text-xs">
                <a href="{{ route('kasir.meja.edit', $meja) }}" class="text-coffee-700 hover:text-coffee-900 font-medium">Ubah</a>
                <form method="POST" action="{{ route('kasir.meja.destroy', $meja) }}" onsubmit="return confirm('Hapus meja {{ $meja->nomor_meja }}?')">
                    @csrf @method('DELETE')
                    <button class="text-red-500 hover:text-red-600 font-medium">Hapus</button>
                </form>
            </div>
        </div>
    @empty
        <p class="text-sm text-ink/40 col-span-4">Belum ada meja. Tambahkan meja untuk mulai cetak QR code.</p>
    @endforelse
</div>
@endsection
