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

    <body class="h-screen min-h-screen text-zinc-900 antialiased dark:bg-zinc-950 dark:text-zinc-100 lg:flex">
        <div x-data="{ isOpen: false }" @click.outside="isOpen = false">
            {{-- Sidebar --}}
            <nav class="fixed inset-y-0 left-0 z-10 h-screen w-72 transform p-4 text-gray-50 transition duration-300 dark:bg-zinc-900 lg:relative lg:translate-x-0 lg:opacity-100 dark:lg:bg-zinc-950"
                :class="{ '-translate-x-full opacity-0': !isOpen, 'translate-x-0 opacity-100': isOpen }" x-transition>

                {{-- Navbar header --}}
                <div class="flex items-center justify-between py-2">
                    <span>PT Birdie Indonesia</span>
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
            </nav>

            {{-- Header --}}
            <header class="block p-3 lg:hidden">
                <div class="rounded-xl border px-4 py-3 dark:border-zinc-700 dark:bg-zinc-900">
                    <x-filament::icon-button @click="isOpen = !isOpen" icon="icon-panel-left-open" size="sm" />
                </div>
            </header>
        </div>


        {{-- Main Content --}}
        <main class="m-2 grow rounded-xl p-6 lg:border dark:lg:border-zinc-800 dark:lg:bg-zinc-900">
            {{ $slot }}
        </main>
        @livewireScripts
        @filamentScripts
    </body>

</html>
