<div class="fixed bottom-2 left-2 top-2 z-10 flex w-72 transform flex-col justify-between rounded-lg border border-theme bg-cardTheme px-4 py-4 text-gray-50 transition dark:border-darkTheme dark:bg-darkCardTheme lg:bottom-0 lg:top-0 lg:h-screen lg:translate-x-0 lg:border-none lg:bg-theme lg:py-6 lg:opacity-100 dark:lg:bg-darkTheme"
    :class="{ '-translate-x-full opacity-0': !isOpen, 'translate-x-0 opacity-100': isOpen }">

    <div>
        {{-- Navbar header --}}

        <div class="flex items-center gap-x-3">
            <img :src="logo" alt="Logo" class="h-auto w-10">
            <p class="text-base text-theme dark:text-darkTheme">PT Birdie Indonesia</p>
        </div>

        {{-- Navbar List --}}
        <div class="mt-5">

            <ul class="space-y-2">
                <li>
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        Dashboard
                    </x-nav-link>
                </li>
            </ul>

        </div>
    </div>

    <div class="hidden lg:block">
        <ul>
            <li class="mb-3">
                <x-theme class="cursor-default" />
            </li>
            <li>
                <x-filament::dropdown>
                    <x-slot name="trigger">
                        <x-filament::button icon-position="after" icon="heroicon-o-chevron-up" outlined
                            class="auth-dropdown w-full cursor-default">
                            {{ Str::words(auth()->user()->name, 2, '') }}
                            <p class="text-xs">{{ auth()->user()->email }}</p>
                        </x-filament::button>
                    </x-slot>

                    <x-filament::dropdown.list>
                        {{-- <x-filament::dropdown.list.item class="auth-signed-as">
                            <div>
                                <p class="text-xs text-darkSecondary">Signed as</p>
                                <p class="text-xs text-darkSecondary">{{ auth()->user()->email }}</p>
                            </div>
                            <hr class="mt-2 border border-theme dark:border-darkTheme">
                        </x-filament::dropdown.list.item> --}}

                        <x-filament::dropdown.list.item icon="heroicon-o-user" tag="a"
                            href="{{ route('profile.edit') }}">
                            Profile Settings
                        </x-filament::dropdown.list.item>

                        <x-filament::dropdown.list.item class="btn-logout" icon="icon-log-out" wire:click='logout'>
                            Log out
                        </x-filament::dropdown.list.item>
                    </x-filament::dropdown.list>

                </x-filament::dropdown>
            </li>

        </ul>
    </div>


</div>
