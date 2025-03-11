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

    <body class="h-screen min-h-screen antialiased text-zinc-900 dark:bg-zinc-950 dark:text-zinc-100 lg:flex">

        <div x-data="{ isOpen: false }" @click.outside="isOpen = false" x-cloak>
            {{-- Sidebar --}}
            <div class="fixed z-10 px-4 py-2 transition duration-300 transform rounded-lg bottom-2 left-2 top-2 w-72 text-gray-50 ring-1 ring-zinc-950/50 dark:bg-zinc-900 dark:ring-zinc-700 lg:h-screen lg:translate-x-0 lg:opacity-100 lg:ring-0 dark:lg:bg-zinc-950"
                :class="{ '-translate-x-full opacity-0': !isOpen, 'translate-x-0 opacity-100': isOpen }">

                {{-- Navbar header --}}
                <div class="flex items-center justify-between py-2">
                    <div class="flex items-center gap-x-3">
                        <img src="{{ asset('img/logo-birdie-hexagon.png') }}" alt="logo" class="w-10 h-auto">
                        <p class="text-sm lg:text-base">PT Birdie Indonesia</p>
                    </div>
                    <x-filament::icon-button @click="isOpen = false" icon="icon-panel-right-open" size="sm"
                        class="block lg:hidden" />
                </div>

                {{-- List Item --}}
                <div class="mt-3">
                    <ul class="space-y-2">
                        <li>
                            <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                                Dashboard
                            </x-nav-link>
                        </li>
                        <li>
                            <x-nav-link href="/">
                                Test
                            </x-nav-link>
                        </li>
                        <li>
                            <x-nav-link href="/">
                                Test
                            </x-nav-link>
                        </li>
                    </ul>
                </div>

            </div>


            {{-- Header --}}
            <header class="block p-3 lg:hidden">
                <div class="px-4 py-3 border rounded-xl dark:border-zinc-700 dark:bg-zinc-900">
                    <x-filament::icon-button @click="isOpen = !isOpen" icon="icon-panel-left-open" size="sm" />
                </div>
            </header>
        </div>


        {{-- Main Content --}}
        <main class="m-2 grow rounded-xl p-6 lg:ms-[19rem] lg:border dark:lg:border-zinc-700 dark:lg:bg-zinc-900">
            {{ $slot }}
        </main>
        @livewireScripts
        @filamentScripts
    </body>

</html>
