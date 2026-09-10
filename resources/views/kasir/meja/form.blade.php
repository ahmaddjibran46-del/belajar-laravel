@extends('layouts.kasir')

@section('title', $meja->exists ? 'Ubah Meja' : 'Tambah Meja')

@section('content')
<a href="{{ route('kasir.meja.index') }}" class="inline-flex items-center gap-1.5 text-sm text-ink/50 hover:text-ink">
    <x-icon name="arrow-left" class="w-4 h-4" /> Kembali
</a>

<div class="mt-4 max-w-sm bg-white rounded-2xl border border-black/5 p-6">
    <h1 class="text-lg font-semibold text-ink mb-4">{{ $meja->exists ? 'Ubah Meja' : 'Tambah Meja' }}</h1>
    <form method="POST" action="{{ $meja->exists ? route('kasir.meja.update', $meja) : route('kasir.meja.store') }}">
        @csrf
        @if ($meja->exists) @method('PUT') @endif
        <label class="block text-sm text-ink/70 mb-1">Nomor Meja</label>
        <input type="text" name="nomor_meja" value="{{ old('nomor_meja', $meja->nomor_meja) }}" required
               class="w-full rounded-lg border border-black/10 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-coffee-500">
        @error('nomor_meja')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror

        <button class="w-full mt-4 bg-coffee-700 hover:bg-coffee-800 text-white text-sm font-medium rounded-lg py-2.5 transition">
            Simpan
        </button>
    </form>
</div>
@endsection
