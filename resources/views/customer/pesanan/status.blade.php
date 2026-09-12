@extends('layouts.customer')

@section('title', 'Pesanan ' . $pesanan->kode_pesanan)

@section('content')

@if ($pesanan->status === 'menunggu_pembayaran')
    {{-- ================= DETAIL PEMBAYARAN ================= --}}
    <div class="px-5 pt-6 pb-2 flex items-center gap-3">
        <a href="#" onclick="event.preventDefault(); history.back();" class="text-char/60 hover:text-char">
            <x-icon name="arrow-left" class="w-5 h-5" />
        </a>
        <h1 class="font-display text-2xl text-char">Detail Pembayaran</h1>
    </div>

    <div class="px-5 mt-3">
        <span id="status-badge" class="inline-block px-3 py-1.5 rounded-full text-sm font-medium {{ $pesanan->warnaStatus() }}">
            {{ $pesanan->labelStatus() }}
        </span>
        <p class="text-xs text-char/40 mt-2">Kode Pesanan &middot; <span class="font-medium text-char/60">{{ $pesanan->kode_pesanan }}</span></p>
    </div>

    @if (session('success'))
        <div class="mx-5 mt-4 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-700 px-4 py-3 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="px-5 mt-4">
        <div class="bg-white rounded-2xl border border-black/5 p-5">
            @if ($pesanan->metode_bayar === 'e_wallet')
                {{-- E-Wallet via Midtrans -- pelanggan bayar online langsung dari HP.
                     Pemilihan metode bayar spesifik (QRIS/GoPay/dsb) dilakukan di dalam popup Midtrans-nya sendiri. --}}
                <p class="text-sm text-char/60 mb-1">Total pembayaran:</p>
                <p class="font-display text-3xl text-char mb-5">Rp{{ number_format($pesanan->total_harga, 0, ',', '.') }}</p>

                @if (config('midtrans.server_key'))
                    <a href="{{ route('pesanan.bayarMidtrans', $pesanan->kode_pesanan) }}"
                       class="inline-block w-full text-center bg-gold-600 hover:bg-gold-700 text-white font-semibold rounded-xl py-3.5 text-sm transition">
                        Bayar Sekarang
                    </a>

                    <button id="btn-cek-ulang" type="button"
                        class="w-full mt-2 border border-black/10 text-char/60 hover:text-char font-medium rounded-xl py-2.5 text-sm transition">
                        Sudah Bayar? Cek Ulang Status
                    </button>
                    <p id="hasil-cek-ulang" class="text-xs text-char/40 mt-2"></p>

                    <p class="text-xs text-char/40 mt-3">Kamu akan diarahkan ke jendela pembayaran resmi Midtrans (mode sandbox/uji coba). Setelah bayar, pesanan otomatis diteruskan ke dapur. Kalau statusnya tidak otomatis berubah, klik "Cek Ulang Status" di atas.</p>
                @else
                    <p class="text-sm text-red-500">Pembayaran e-wallet belum diaktifkan oleh kasir. Silakan hubungi kasir untuk membayar secara manual.</p>
                @endif
            @else
                {{-- Bayar manual di kasir -- kasir yang konfirmasi, tidak otomatis --}}
                <div class="text-center">
                    <div class="w-14 h-14 rounded-full bg-coffee-50 text-coffee-700 flex items-center justify-center mx-auto mb-3">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" class="w-7 h-7"><rect x="2.5" y="6" width="19" height="12" rx="2.5"/><path d="M2.5 10h19"/></svg>
                    </div>
                    <p class="font-semibold text-char">Silakan Bayar di Kasir</p>
                    <p class="text-sm text-char/50 mt-1">Tunjukkan kode pesanan ini ke kasir untuk membayar.</p>

                    <p class="text-xs text-char/40 mt-4">Total Pembayaran</p>
                    <p class="font-display text-3xl text-char">Rp{{ number_format($pesanan->total_harga, 0, ',', '.') }}</p>

                    <div class="mt-4 inline-block bg-coffee-50 rounded-xl px-5 py-3">
                        <p class="text-[11px] text-coffee-700/60 uppercase tracking-wide">Kode Pesanan</p>
                        <p class="font-mono text-xl font-semibold text-coffee-800">{{ $pesanan->kode_pesanan }}</p>
                    </div>

                    <p class="text-xs text-char/40 mt-4">Halaman ini akan otomatis berubah begitu kasir mengonfirmasi pembayaranmu.</p>
                </div>
            @endif
        </div>
    </div>
@else
    {{-- ================= STATUS LAIN (diproses / siap / selesai / dibatalkan) ================= --}}
    <div class="px-5 pt-8 pb-2 flex justify-center">
        <x-brand-logo size="md" />
    </div>

    <div class="px-5 pt-6 pb-4 text-center">
        @php
            $iconByStatus = [
                'dibayar' => 'check',
                'diproses' => 'check',
                'siap' => 'check',
                'selesai' => 'check',
                'dibatalkan' => 'minus',
            ];
        @endphp
        <div class="relative inline-block mx-auto mb-4">
            <svg viewBox="0 0 32 32" fill="none" class="w-20 h-20 {{ $pesanan->status === 'dibatalkan' ? 'text-red-400' : 'text-coffee-700' }}">
                <path d="M11 6.5c0-1 .8-1.5.8-2.5S11 2.5 11 2.5" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/>
                <path d="M15.5 6.5c0-1 .8-1.5.8-2.5S15.5 2.5 15.5 2.5" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/>
                <path d="M20 6.5c0-1 .8-1.5.8-2.5S20 2.5 20 2.5" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/>
                <path d="M6.5 12h17.2l-1.1 10.2a3 3 0 0 1-3 2.7H10.6a3 3 0 0 1-3-2.7L6.5 12Z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/>
                <path d="M23.7 14h1.8a2.6 2.6 0 0 1 0 5.2h-2.3" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M4.5 27.5h23" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
            </svg>
            <span class="absolute -bottom-1 -right-1 w-7 h-7 rounded-full flex items-center justify-center border-2 border-cream {{ $pesanan->status === 'dibatalkan' ? 'bg-red-500' : 'bg-coffee-800' }}">
                <x-icon name="{{ $iconByStatus[$pesanan->status] ?? 'check' }}" class="w-4 h-4 text-white" stroke-width="2.4" />
            </span>
        </div>

        <div>
            <span id="status-badge" class="inline-block px-4 py-1.5 rounded-full text-sm font-medium {{ $pesanan->warnaStatus() }}">
                {{ $pesanan->labelStatus() }}
            </span>
        </div>

        @if (session('success'))
            <div class="mt-4 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-700 px-4 py-3 text-sm text-left">
                {{ session('success') }}
            </div>
        @endif

        <div class="mt-4">
            @if (in_array($pesanan->status, ['dibayar', 'diproses']))
                <p class="font-semibold text-char">Pesanan Anda Sedang Kami Siapkan!</p>
                <p class="text-sm text-char/50 mt-1">Halaman ini akan otomatis diperbarui.</p>
            @elseif ($pesanan->status === 'siap')
                <p class="font-semibold text-emerald-600">Pesananmu sudah siap!</p>
                <p class="text-sm text-char/50 mt-1">Silakan ambil / tunggu diantar ke meja.</p>
            @elseif ($pesanan->status === 'selesai')
                <p class="font-semibold text-char">Terima kasih!</p>
                <p class="text-sm text-char/50 mt-1">Selamat menikmati 🎉</p>
            @elseif ($pesanan->status === 'dibatalkan')
                <p class="font-semibold text-red-500">Pesanan ini dibatalkan.</p>
                <p class="text-sm text-char/50 mt-1">Silakan hubungi kasir jika ada pertanyaan.</p>
            @endif
        </div>

        <p class="text-xs text-char/30 mt-3">Kode Pesanan &middot; {{ $pesanan->kode_pesanan }}</p>
    </div>
@endif

<div class="px-5 mt-2">
    <div class="bg-white rounded-2xl border border-black/5 p-4">
        <p class="text-xs text-char/40 mb-2">
            {{ $pesanan->tipe === 'dine_in' ? 'Makan di Tempat' : 'Bawa Pulang' }}
            @if ($pesanan->nomor_meja_snapshot) &middot; Meja {{ $pesanan->nomor_meja_snapshot }} @endif
        </p>
        <div class="divide-y divide-black/5">
            @foreach ($pesanan->items as $item)
                <div class="py-2 flex justify-between text-sm">
                    <div>
                        <p class="text-char">{{ $item->qty }}x {{ $item->nama_menu }}</p>
                        @if ($item->catatan)
                            <p class="text-xs text-char/40">"{{ $item->catatan }}"</p>
                        @endif
                    </div>
                    <p class="text-char/70">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</p>
                </div>
            @endforeach
        </div>
        <div class="pt-3 mt-2 border-t border-black/10 flex justify-between font-semibold text-char">
            <span>Total</span>
            <span>Rp{{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
        </div>
    </div>
</div>

<script>
    // Auto refresh status tiap 5 detik, tanpa reload halaman penuh.
    const kode = @json($pesanan->kode_pesanan);
    let currentStatus = @json($pesanan->status);

    setInterval(async () => {
        try {
            const res = await fetch(`/pesanan/${kode}/cek-status`);
            const data = await res.json();
            if (data.status !== currentStatus) {
                location.reload();
            }
        } catch (e) {}
    }, 5000);

    // Tombol "Sudah Bayar? Cek Ulang Status" -- paksa cek langsung ke server Midtrans.
    const btnCekUlang = document.getElementById('btn-cek-ulang');
    if (btnCekUlang) {
        btnCekUlang.addEventListener('click', async () => {
            btnCekUlang.disabled = true;
            btnCekUlang.textContent = 'Mengecek...';
            const hasilEl = document.getElementById('hasil-cek-ulang');
            try {
                const res = await fetch(@json(route('pesanan.cekStatusMidtrans', $pesanan->kode_pesanan)));
                const data = await res.json();
                if (data.status !== currentStatus) {
                    location.reload();
                } else {
                    hasilEl.textContent = 'Belum terdeteksi ada pembayaran baru. Coba lagi beberapa saat.';
                    btnCekUlang.disabled = false;
                    btnCekUlang.textContent = 'Sudah Bayar? Cek Ulang Status';
                }
            } catch (e) {
                hasilEl.textContent = 'Gagal mengecek status, coba lagi.';
                btnCekUlang.disabled = false;
                btnCekUlang.textContent = 'Sudah Bayar? Cek Ulang Status';
            }
        });
    }
</script>
@endsection