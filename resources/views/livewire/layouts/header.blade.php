<header class="block p-3 lg:hidden">
    <div
        class="flex items-center justify-between rounded-xl border border-theme bg-cardTheme px-4 py-3 dark:border-darkTheme dark:bg-darkCardTheme">
        <x-filament::icon-button @click="isOpen = !isOpen" icon="icon-panel-left-open" size="sm" />

        <div class="flex gap-x-3">
            <x-theme placement="bottom-end" />

            <x-filament::dropdown placement="bottom-end">
                <x-slot name="trigger">
                    <x-filament::button icon-position="after" icon="heroicon-o-chevron-down" outlined
                        class="auth-dropdown w-full cursor-default">
                        {{ auth()->user()->email }}
                    </x-filament::button>
                </x-slot>

                <x-filament::dropdown.list>
                    <x-filament::dropdown.list.item icon="heroicon-o-user">
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
