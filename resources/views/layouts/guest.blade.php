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

    <body class="font-sans antialiased">
        <div class="flex flex-col items-center min-h-screen pt-6 bg-theme dark:bg-darkTheme sm:justify-center sm:pt-0">
            {{-- <div>
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-zinc-500" />
                </a>
            </div> --}}

            <div
                class="w-full px-6 py-4 overflow-hidden border shadow border-theme bg-cardTheme dark:border-darkTheme dark:bg-darkCardTheme sm:max-w-sm sm:rounded-xl">
                {{ $slot }}
            </div>
        </div>

        @livewireScripts
        @filamentScripts
    </body>

</html>
