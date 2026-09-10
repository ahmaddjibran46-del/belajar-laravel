@extends('layouts.auth')

@section('title', 'Masuk Kasir')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-10">
    <div class="w-full max-w-sm">
        <div class="flex justify-center mb-8">
            <x-brand-logo size="lg" />
        </div>

        <div class="bg-white rounded-2xl border border-black/5 shadow-sm p-8">
            <h1 class="text-xl font-semibold text-ink">Masuk sebagai Kasir</h1>
            <p class="text-sm text-ink/50 mt-1 mb-6">Pelanggan tidak perlu masuk — cukup scan QR di meja. Halaman ini khusus staf kasir.</p>

            @if ($errors->any())
                <div class="mb-4 rounded-lg bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm text-ink/70 mb-1">Email</label>
                    <div class="relative">
                        <x-icon name="mail" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-ink/35" />
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="w-full rounded-lg border border-black/10 pl-9 pr-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-coffee-500 focus:border-coffee-500">
                    </div>
                </div>
                <div>
                    <label class="block text-sm text-ink/70 mb-1">Kata Sandi</label>
                    <div class="relative">
                        <x-icon name="lock" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-ink/35" />
                        <input type="password" name="password" required
                            class="w-full rounded-lg border border-black/10 pl-9 pr-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-coffee-500 focus:border-coffee-500">
                    </div>
                </div>
                <button type="submit" class="w-full bg-coffee-700 hover:bg-coffee-800 text-white font-medium rounded-lg py-2.5 text-sm transition">
                    Masuk
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
