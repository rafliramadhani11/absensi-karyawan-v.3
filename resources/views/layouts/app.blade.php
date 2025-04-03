<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{
    darkMode: localStorage.getItem('darkMode') || 'system',

    get logo() {
        return (this.darkMode === 'dark' ||
                (this.darkMode === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)) ?
            '{{ asset('img/logo-birdie-hexagon.png') }}' :
            '{{ asset('img/logo-birdie-hexagon-light.png') }}';
    }
}" x-init="if (!localStorage.getItem('darkMode')) localStorage.setItem('darkMode', 'system');
$watch('darkMode', val => localStorage.setItem('darkMode', val))"
    x-bind:class="{
        'dark': darkMode === 'dark' ||
            (darkMode === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)
    }">

    <head>
        @include('partials.head')

        @stack('scripts')
    </head>

    <body class="h-full min-h-screen antialiased bg-theme text-zinc-900 dark:bg-zinc-950 dark:text-zinc-100 lg:flex">

        <div x-data="{ isOpen: false }" @click.outside="isOpen = false" x-cloak>
            {{-- Sidebar --}}
            <livewire:layouts.sidebar />


            {{-- Header --}}
            <livewire:layouts.header />
        </div>


        {{-- Main Content --}}
        <main
            class="mx-4 my-2 grow overflow-auto rounded-xl lg:m-2 lg:ms-[19rem] lg:border lg:border-theme lg:bg-cardTheme lg:px-8 lg:py-6 dark:lg:border-darkTheme dark:lg:bg-darkCardTheme">

            {{ $slot }}
        </main>


        @livewire('notifications')

        @livewireScripts
        @filamentScripts
    </body>

</html>
