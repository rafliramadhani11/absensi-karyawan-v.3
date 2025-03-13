<div class="fixed bottom-2 left-2 top-2 z-10 w-72 transform rounded-lg border border-theme px-4 py-2 text-gray-50 transition duration-300 dark:border-darkTheme dark:bg-darkCardTheme lg:h-screen lg:translate-x-0 lg:border-none lg:opacity-100 dark:lg:bg-darkTheme"
    :class="{ '-translate-x-full opacity-0': !isOpen, 'translate-x-0 opacity-100': isOpen }">

    {{-- Navbar header --}}
    <div class="flex items-center justify-between py-2">
        <div class="flex items-center gap-x-3">
            <img src="{{ asset('img/logo-birdie-hexagon.png') }}" alt="logo" class="h-auto w-10">
            <p class="text-sm lg:text-base">PT Birdie Indonesia</p>
        </div>
        <x-filament::icon-button @click="isOpen = false" icon="icon-panel-right-open" size="sm"
            class="block lg:hidden" />
    </div>

    {{-- Navbar List --}}
    <div class="mt-3">
        <ul class="space-y-2">
            <li>
                <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    Dashboard
                </x-nav-link>
            </li>
        </ul>
    </div>

</div>
