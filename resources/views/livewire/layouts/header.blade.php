<header class="block px-4 py-3 lg:hidden">
    <div
        class="flex items-center justify-between rounded-xl border border-theme bg-cardTheme px-4 py-3 dark:border-darkTheme dark:bg-darkCardTheme">
        <x-filament::icon-button @click="isOpen = !isOpen" icon="icon-panel-left-open" size="sm" />

        <div class="flex sm:gap-x-3 lg:gap-x-0">
            <x-theme placement="bottom-end" />

            <x-filament::dropdown placement="bottom-end">
                <x-slot name="trigger">
                    <x-filament::button icon-position="after" icon="heroicon-o-chevron-down" outlined
                        class="auth-dropdown w-full cursor-default text-xs sm:text-sm">
                        {{ Str::words(auth()->user()->name, 2, '') }}
                    </x-filament::button>
                </x-slot>

                <x-filament::dropdown.list>
                    <x-filament::dropdown.list.item class="auth-signed-as">
                        <div>
                            <p class="text-xs text-darkSecondary dark:text-darkSecondary">Signed as</p>
                            <p class="text-xs text-darkSecondary dark:text-darkSecondary">{{ auth()->user()->email }}
                            </p>
                        </div>
                        <hr class="mt-2 border border-theme dark:border-darkTheme">
                    </x-filament::dropdown.list.item>

                    <x-filament::dropdown.list.item icon="heroicon-o-user" href="{{ route('profile.edit') }}"
                        tag="a">
                        Profile Settings
                    </x-filament::dropdown.list.item>

                    <x-filament::dropdown.list.item class="btn-logout" icon="icon-log-out" wire:click='logout'>
                        Log out
                    </x-filament::dropdown.list.item>
                </x-filament::dropdown.list>

            </x-filament::dropdown>
        </div>
    </div>
</header>
