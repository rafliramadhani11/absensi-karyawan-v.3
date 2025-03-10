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

    <body class="font-sans text-gray-900 antialiased">
        <div class="flex min-h-screen flex-col items-center bg-zinc-100 pt-6 dark:bg-zinc-950 sm:justify-center sm:pt-0">
            {{-- <div>
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-zinc-500" />
                </a>
            </div> --}}

            <div
                class="w-full overflow-hidden border bg-white px-6 py-4 shadow dark:border-zinc-800 dark:bg-zinc-900 sm:max-w-sm sm:rounded-xl">
                {{ $slot }}
            </div>
        </div>
    </body>

</html>
