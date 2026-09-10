@extends('layouts.customer')

@section('title', 'Bayar Pesanan')

@section('content')
<div class="px-5 pt-14 text-center">
    <div class="flex justify-center mb-6">
        <x-brand-logo size="md" />
    </div>
    <p class="text-xs uppercase tracking-wide text-char/40">Kode Pesanan</p>
    <h1 class="font-display text-2xl text-char mt-1">{{ $pesanan->kode_pesanan }}</h1>
    <p class="font-display text-3xl text-chili-700 mt-3">Rp{{ number_format($pesanan->total_harga, 0, ',', '.') }}</p>
    <div class="mt-6 flex justify-center">
        <div class="w-8 h-8 border-2 border-coffee-200 border-t-coffee-700 rounded-full animate-spin"></div>
    </div>
    <p id="pesan-status" class="text-sm text-char/50 mt-4">Menyiapkan halaman pembayaran...</p>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
    const statusUrl = @json(route('pesanan.status', $pesanan->kode_pesanan));
    const cekStatusUrl = @json(route('pesanan.cekStatusMidtrans', $pesanan->kode_pesanan));
    const pesanStatus = document.getElementById('pesan-status');

    async function verifikasiLaluKembali(pesanSementara) {
        pesanStatus.textContent = pesanSementara;
        try {
            await fetch(cekStatusUrl);
        } catch (e) {}
        window.location.href = statusUrl;
    }

    window.snap.pay(@json($snapToken), {
        onSuccess: function (result) {
            verifikasiLaluKembali('Pembayaran berhasil, mengalihkan...');
        },
        onPending: function (result) {
            verifikasiLaluKembali('Menunggu penyelesaian pembayaran...');
        },
        onError: function (result) {
            pesanStatus.textContent = 'Terjadi kesalahan saat memproses pembayaran.';
        },
        onClose: function () {
            verifikasiLaluKembali('Jendela pembayaran ditutup, mengalihkan...');
        }
    });
</script>
@endsection
