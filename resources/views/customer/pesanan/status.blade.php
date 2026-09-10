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
            @if (config('midtrans.server_key'))
                {{-- Opsi 1: Midtrans Sandbox -- pelanggan bayar online langsung dari HP --}}
                <p class="font-semibold text-char mb-1">Payment option (QRIS)</p>
                <p class="text-xs text-char/40 mb-3">(optional)</p>
                <div class="grid grid-cols-4 gap-2 mb-5">
                    <div class="aspect-square rounded-xl border-2 border-chili-500 bg-chili-50 flex items-center justify-center text-[10px] font-bold text-chili-700">QRIS</div>
                    <div class="aspect-square rounded-xl border border-black/10 flex items-center justify-center text-char/50">
                        <svg viewBox="0 0 24 24" class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><path d="M14 14h3v3h-3zM19 14h2M14 19h2M19 19h2"/></svg>
                    </div>
                    <div class="aspect-square rounded-xl border border-black/10 flex items-center justify-center text-char/50">
                        <svg viewBox="0 0 24 24" class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2.5" y="6" width="19" height="12" rx="2.5"/><path d="M2.5 10h19"/></svg>
                    </div>
                    <div class="aspect-square rounded-xl border border-black/10 flex items-center justify-center text-char/50">
                        <svg viewBox="0 0 24 24" class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="9"/><path d="M9 12h6"/></svg>
                    </div>
                </div>
                <p class="text-sm text-char/60 mb-1">Total pembayaran:</p>
                <p class="font-display text-2xl text-char mb-4">Rp{{ number_format($pesanan->total_harga, 0, ',', '.') }}</p>
                <a href="{{ route('pesanan.bayarMidtrans', $pesanan->kode_pesanan) }}"
                   class="inline-block w-full text-center bg-gold-600 hover:bg-gold-700 text-white font-semibold rounded-xl py-3.5 text-sm transition">
                    Bayar Sekarang
                </a>

                <button id="btn-cek-ulang" type="button"
                    class="w-full mt-2 border border-black/10 text-char/60 hover:text-char font-medium rounded-xl py-2.5 text-sm transition">
                    Sudah Bayar? Cek Ulang Status
                </button>
                <p id="hasil-cek-ulang" class="text-xs text-char/40 mt-2"></p>

                <p class="text-xs text-char/40 mt-3">Kamu akan diarahkan ke jendela pembayaran resmi Midtrans (mode sandbox/uji coba). Kalau setelah bayar status tidak otomatis berubah, klik tombol "Cek Ulang Status" di atas.</p>
            @elseif ($pengaturan->qris_gambar_url)
                {{-- Opsi 2: QRIS gambar statis milik cafe --}}
                <p class="text-sm text-char/60 mb-1">Scan QRIS di bawah ini pakai HP kamu ({{ $pengaturan->nama_usaha ?? 'GoPay/OVO/DANA/m-Banking' }}):</p>
                <p class="font-display text-2xl text-char my-2">Rp{{ number_format($pesanan->total_harga, 0, ',', '.') }}</p>
                <img src="{{ $pengaturan->qris_gambar_url }}" alt="QRIS Pembayaran" class="mx-auto rounded-xl border border-black/5 w-56 h-56 object-contain">

                <form method="POST" action="{{ route('pesanan.konfirmasiBayar', $pesanan->kode_pesanan) }}" class="mt-4">
                    @csrf
                    <button type="submit"
                        onclick="return confirm('Pastikan kamu sudah benar-benar transfer Rp{{ number_format($pesanan->total_harga, 0, ',', '.') }} sebelum lanjut ya.')"
                        class="w-full bg-gold-600 hover:bg-gold-700 text-white font-semibold rounded-xl py-3.5 text-sm transition">
                        Saya Sudah Bayar
                    </button>
                </form>
                <p class="text-xs text-char/40 mt-3">Setelah transfer &amp; klik tombol di atas, pesananmu langsung diteruskan ke dapur. Kasir akan mencocokkan pembayaran di sistem mereka.</p>
            @else
                {{-- Opsi 3 (fallback): tunjukkan kode ke kasir --}}
                <p class="text-sm text-char/60 mb-3">Silakan bayar di kasir untuk melanjutkan pesanan. Tunjukkan kode/QR ini ke kasir:</p>
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data={{ urlencode($pesanan->kode_pesanan) }}"
                     alt="QR Pesanan" class="mx-auto rounded-xl border border-black/5">
                <p class="text-xs text-char/40 mt-3">Simpan halaman ini terbuka sampai kamu selesai bayar di kasir.</p>
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
