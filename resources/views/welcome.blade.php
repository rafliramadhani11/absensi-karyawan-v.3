<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{
    darkMode: localStorage.getItem('darkMode') ||
        localStorage.setItem('darkMode', 'system')
}" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))"
    x-bind:class="{
        'dark': darkMode === 'dark' || (darkMode === 'system' && window.matchMedia('(prefers-color-scheme: dark)')
            .matches)
    }">



    <head>

        <meta charset="utf-8">

        <meta name="application-name" content="{{ config('app.name') }}">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="icon" type="image/png" href="{{ asset('img/logo-birdie-hexagon.png') }}">
        <link rel="icon" type="image/x-icon" href="{{ asset('img/logo-birdie-hexagon.png') }}">


        <title>Welcome - {{ config('app.name') }}</title>


        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link
            href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
            rel="stylesheet">

        <style>
            [x-cloak] {
                display: none !important;
            }
        </style>

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
        @filamentStyles

        <script>
            let theme = localStorage.getItem('darkMode') || 'system';

            if (theme === 'dark' || (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>

    </head>

    <body class="flex min-h-screen flex-col items-center gap-4 bg-theme px-6 dark:bg-darkTheme lg:justify-center">
        <header class="mt-6 flex w-full justify-end lg:max-w-4xl">
            <x-theme />
        </header>

        <div
            class="duration-750 -mt-6 flex w-full grow items-center justify-center opacity-100 transition-opacity lg:max-w-4xl">
            <main class="w-full lg:max-w-4xl">
                <div class="flex flex-col justify-center leading-[20px]">
                    <h1 class="mb-1 text-3xl font-medium dark:text-darkTheme">Absensi Karyawan Qr Code</h1>
                    <p class="mb-2 text-secondary dark:text-darkSecondary"> Solusi praktis untuk kehadiran karyawan
                        dengan
                        scan QR Code. Cepat, efisien, dan akurat. </p>
                    <div class="w-full text-sm">
                        @if (Route::has('login'))
                            <nav class="flex items-center justify-start gap-4">
                                @auth
                                    <x-primary-button href="{{ route('dashboard') }}" tag="a">
                                        Dashboard
                                    </x-primary-button>
                                @else
                                    <x-primary-button href="{{ route('login') }}" tag="a">
                                        Log in
                                    </x-primary-button>

                                    @if (Route::has('register'))
                                        <x-secondary-button href="{{ route('register') }}" tag="a">
                                            Register
                                        </x-secondary-button>
                                    @endif
                                @endauth
                            </nav>
                        @endif
                    </div>
                </div>
            </main>
        </div>

        @livewireScripts
        @filamentScripts
    </body>

</html>
