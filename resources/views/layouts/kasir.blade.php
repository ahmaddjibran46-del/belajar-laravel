<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Kasir')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        display: ['Georgia', 'ui-serif', 'serif'],
                    },
                    colors: {
                        chili: {
                            50: '#FEF4F1',
                            100: '#FBE2DA',
                            400: '#E6603F',
                            500: '#C8432A',
                            600: '#A8341F',
                            700: '#832818',
                        },
                        coffee: {
                            50: '#FAF4EE',
                            100: '#F1E1D2',
                            200: '#E3CBB4',
                            300: '#C9A883',
                            400: '#8B5A3C',
                            500: '#6B3D22',
                            600: '#54301A',
                            700: '#442512',
                            800: '#341A0C',
                            900: '#241007',
                        },
                        gold: {
                            50: '#FDF6E9',
                            100: '#FBEACB',
                            400: '#DDA23D',
                            500: '#C88C3C',
                            600: '#B4791E',
                            700: '#8F5F17',
                        },
                        ink: '#15130F',
                        panel: '#1D1A16',
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-[#F5EFE6] text-ink antialiased min-h-screen">
    <div class="flex min-h-screen">
        <aside class="w-64 shrink-0 bg-coffee-900 text-white/90 flex flex-col">
            <div class="px-5 py-6">
                <x-brand-logo variant="white" size="sm" />
            </div>
            <div class="px-5 pb-4">
                <p class="text-base font-semibold text-white">Dapur Kasir</p>
                <p class="text-xs text-white/40 mt-0.5">Panel Kasir</p>
            </div>
            <nav class="flex-1 px-3 py-2 space-y-1 text-sm">
                @php
                    $navItems = [
                        ['route' => 'kasir.dashboard', 'is' => 'kasir.dashboard', 'label' => 'Antrian Pesanan', 'icon' => 'grid'],
                        ['route' => 'kasir.menu.index', 'is' => 'kasir.menu.*', 'label' => 'Kelola Menu', 'icon' => 'tag'],
                        ['route' => 'kasir.kategori.index', 'is' => 'kasir.kategori.*', 'label' => 'Kategori', 'icon' => 'grid'],
                        ['route' => 'kasir.meja.index', 'is' => 'kasir.meja.*', 'label' => 'Meja & QR Code', 'icon' => 'table'],
                    ];
                @endphp
                @foreach ($navItems as $item)
                    @php $active = request()->routeIs($item['is']); @endphp
                    <a href="{{ route($item['route']) }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ $active ? 'bg-white/10 text-white' : 'text-white/60 hover:bg-white/5 hover:text-white/90' }}">
                        <x-icon :name="$item['icon']" class="w-5 h-5 shrink-0 {{ $active ? 'text-gold-400' : '' }}" />
                        <span>{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </nav>
            <div class="px-5 py-4 border-t border-white/10 flex items-center gap-3">
                <div
                    class="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center text-sm font-semibold text-white shrink-0">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-xs text-white/40">Admin Kasir</p>
                    <p class="text-sm text-white/85 truncate">{{ auth()->user()->name }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button title="Keluar" class="text-white/40 hover:text-white transition">
                        <x-icon name="logout" class="w-4 h-4" />
                    </button>
                </form>
            </div>
        </aside>

        <main class="flex-1 px-8 py-7 max-w-6xl">
            @if (session('success'))
                <div class="mb-5 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 text-sm">
                    {{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="mb-5 rounded-lg bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-sm">
                    {{ session('error') }}</div>
            @endif
            @yield('content')
        </main>
    </div>
</body>

</html>