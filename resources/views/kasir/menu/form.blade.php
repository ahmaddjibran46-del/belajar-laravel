@extends('layouts.kasir')

@section('title', $menu->exists ? 'Ubah Menu' : 'Tambah Menu')

@section('content')
<a href="{{ route('kasir.menu.index') }}" class="inline-flex items-center gap-1.5 text-sm text-ink/50 hover:text-ink">
    <x-icon name="arrow-left" class="w-4 h-4" /> Kembali
</a>

<div class="mt-4 max-w-md bg-white rounded-2xl border border-black/5 p-6">
    <h1 class="text-lg font-semibold text-ink mb-4">{{ $menu->exists ? 'Ubah Menu' : 'Tambah Menu' }}</h1>
    <form method="POST" action="{{ $menu->exists ? route('kasir.menu.update', $menu) : route('kasir.menu.store') }}" enctype="multipart/form-data" class="space-y-4">
        @csrf
        @if ($menu->exists) @method('PUT') @endif

        <div>
            <label class="block text-sm text-ink/70 mb-1">Kategori</label>
            <select name="kategori_id" required class="w-full rounded-lg border border-black/10 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-coffee-500">
                <option value="">Pilih kategori</option>
                @foreach ($kategoris as $kategori)
                    <option value="{{ $kategori->id }}" @selected(old('kategori_id', $menu->kategori_id) == $kategori->id)>{{ $kategori->nama }}</option>
                @endforeach
            </select>
            @error('kategori_id')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm text-ink/70 mb-1">Nama Menu</label>
            <input type="text" name="nama" value="{{ old('nama', $menu->nama) }}" required
                   class="w-full rounded-lg border border-black/10 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-coffee-500">
            @error('nama')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm text-ink/70 mb-1">Deskripsi</label>
            <textarea name="deskripsi" rows="2" class="w-full rounded-lg border border-black/10 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-coffee-500">{{ old('deskripsi', $menu->deskripsi) }}</textarea>
        </div>

        <div>
            <label class="block text-sm text-ink/70 mb-1">Harga (Rp)</label>
            <input type="number" name="harga" value="{{ old('harga', $menu->harga) }}" required min="0"
                   class="w-full rounded-lg border border-black/10 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-coffee-500">
            @error('harga')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm text-ink/70 mb-1">Status</label>
            <select name="status" class="w-full rounded-lg border border-black/10 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-coffee-500">
                <option value="tersedia" @selected(old('status', $menu->status ?? 'tersedia') == 'tersedia')>Tersedia</option>
                <option value="habis" @selected(old('status', $menu->status) == 'habis')>Habis</option>
            </select>
        </div>

        <div>
            <label class="block text-sm text-ink/70 mb-1">Foto Menu (opsional)</label>
            <input type="file" name="gambar" accept="image/*" class="w-full text-sm">
            @if ($menu->gambar_url)
                <img src="{{ $menu->gambar_url }}" class="w-20 h-20 object-cover rounded-lg mt-2 border border-black/5">
            @endif
        </div>

        <button class="w-full bg-coffee-700 hover:bg-coffee-800 text-white text-sm font-medium rounded-lg py-2.5 transition">
            Simpan
        </button>
    </form>
</div>
@endsection
