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
        @include('partials.head')
    </head>

    <body
        class="flex min-h-screen flex-col items-center gap-4 bg-white px-6 text-[#1b1b18] dark:bg-zinc-950 lg:justify-center">
        <header class="flex justify-end w-full mt-6 lg:max-w-4xl">
            <x-theme />
        </header>

        <div
            class="flex items-center justify-center w-full -mt-6 transition-opacity opacity-100 duration-750 grow lg:max-w-4xl">
            <main class="w-full lg:max-w-4xl">

                <p>trying new branch</p>

                <div class="flex flex-col justify-center text-[13px] leading-[20px] dark:text-[#EDEDEC]">
                    <h1 class="mb-1 text-3xl font-medium">Absensi Karyawan Qr Code</h1>
                    <p class="mb-2 text-[#706f6c] dark:text-[#A1A09A]"> Solusi praktis untuk kehadiran karyawan dengan
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
