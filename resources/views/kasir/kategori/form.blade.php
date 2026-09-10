@extends('layouts.kasir')

@section('title', $kategori->exists ? 'Ubah Kategori' : 'Tambah Kategori')

@section('content')
<a href="{{ route('kasir.kategori.index') }}" class="inline-flex items-center gap-1.5 text-sm text-ink/50 hover:text-ink">
    <x-icon name="arrow-left" class="w-4 h-4" /> Kembali
</a>

<div class="mt-4 max-w-sm bg-white rounded-2xl border border-black/5 p-6">
    <h1 class="text-lg font-semibold text-ink mb-4">{{ $kategori->exists ? 'Ubah Kategori' : 'Tambah Kategori' }}</h1>
    <form method="POST" action="{{ $kategori->exists ? route('kasir.kategori.update', $kategori) : route('kasir.kategori.store') }}">
        @csrf
        @if ($kategori->exists) @method('PUT') @endif

        <label class="block text-sm text-ink/70 mb-1">Nama Kategori</label>
        <input type="text" name="nama" value="{{ old('nama', $kategori->nama) }}" required
               class="w-full rounded-lg border border-black/10 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-coffee-500">
        @error('nama')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror

        <label class="block text-sm text-ink/70 mb-1 mt-4">Urutan Tampil</label>
        <input type="number" name="urutan" value="{{ old('urutan', $kategori->urutan ?? 0) }}"
               class="w-full rounded-lg border border-black/10 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-coffee-500">

        <button class="w-full mt-4 bg-coffee-700 hover:bg-coffee-800 text-white text-sm font-medium rounded-lg py-2.5 transition">
            Simpan
        </button>
    </form>
</div>
@endsection
