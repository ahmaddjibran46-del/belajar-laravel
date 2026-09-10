<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
    <title>@yield('title', 'Pesan Menu')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        display: ['Georgia', 'ui-serif', 'serif'],
                        sans: ['-apple-system', 'system-ui', 'sans-serif'],
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
                        cream: '#FBF6EF',
                        char: '#241B17',
                    }
                }
            }
        }
    </script>
    @stack('head')
</head>
<body class="bg-cream text-char font-sans antialiased min-h-screen pb-28">
    <div class="max-w-md mx-auto min-h-screen bg-cream shadow-sm relative">
        @yield('content')
    </div>
</body>
</html>
