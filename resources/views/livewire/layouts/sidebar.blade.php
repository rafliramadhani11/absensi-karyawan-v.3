<div class="fixed z-10 flex flex-col justify-between px-4 py-4 transition transform border rounded-lg bottom-2 left-2 top-2 w-72 border-theme bg-cardTheme text-gray-50 dark:border-darkTheme dark:bg-darkCardTheme lg:bottom-0 lg:top-0 lg:h-screen lg:translate-x-0 lg:border-none lg:bg-theme lg:py-6 lg:opacity-100 dark:lg:bg-darkTheme"
    :class="{ '-translate-x-full opacity-0': !isOpen, 'translate-x-0 opacity-100': isOpen }">

    <div>
        {{-- Navbar header --}}

        <div class="flex items-center gap-x-3">

            <img :src="logo" alt="Logo" class="w-10 h-auto">

            <p class="text-base text-theme dark:text-darkTheme">PT Birdie Indonesia</p>
        </div>

        {{-- Navbar List --}}
        <div class="mt-5">
            <ul class="space-y-2">

                @admin
                    <li>
                        <x-nav-link wire:navigate :href="route('admin.employees-qr-code')" :active="request()->routeIs('admin.employees-qr-code*')">
                            <div class="flex">
                                <x-filament::icon icon="icon-qr-code" class="w-5 h-5 me-3 text-theme dark:text-darkTheme" />
                                Employees Qr Code
                            </div>
                        </x-nav-link>
                    </li>
                @endadmin

                @hrdAndEmployee
                    <li>
                        <x-nav-link wire:navigate :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                            <div class="flex">
                                <x-filament::icon icon="heroicon-o-home"
                                    class="w-5 h-5 me-3 text-theme dark:text-darkTheme" />
                                Dashboard
                            </div>
                        </x-nav-link>
                    </li>
                @endhrdAndEmployee

                @hrdAndEmployee
                    <li>
                        <ul class="space-y-2">
                            <p class="mt-5 mb-3 text-xs text-secondary dark:text-darkSecondary">App</p>
                            <li>
                                <x-nav-link :href="route('absent.index')" :active="request()->routeIs('absent*')">
                                    <div class="flex">
                                        <x-filament::icon icon="icon-scan-qr-code"
                                            class="w-5 h-5 me-3 text-theme dark:text-darkTheme" />
                                        Qr Code Absent
                                    </div>
                                </x-nav-link>
                            </li>
                        </ul>
                    </li>
                @endhrdAndEmployee

                @hrd
                    <li>
                        <ul class="space-y-2">
                            <p class="mt-5 mb-3 text-xs text-secondary dark:text-darkSecondary">Resources</p>

                            <li>
                                <x-nav-link wire:navigate :href="route('division.index')" :active="request()->routeIs('division*')">
                                    <div class="flex">
                                        <x-filament::icon icon="heroicon-o-user-group"
                                            class="w-5 h-5 me-3 text-theme dark:text-darkTheme" />
                                        Divisions
                                    </div>
                                    <x-filament::badge class="sidebar-badge">
                                        {{ $this->divisionCount }}
                                    </x-filament::badge>
                                </x-nav-link>
                            </li>

                            <li>
                                <x-nav-link wire:navigate :href="route('user.index')" :active="request()->routeIs('user*')">
                                    <div class="flex">
                                        <x-filament::icon icon="heroicon-o-user"
                                            class="w-5 h-5 me-3 text-theme dark:text-darkTheme" />
                                        Employees
                                    </div>
                                    <x-filament::badge class="sidebar-badge">
                                        {{ $this->userCount }}
                                    </x-filament::badge>
                                </x-nav-link>
                            </li>

                            <li>
                                <x-nav-link wire:navigate :href="route('attendance.index')" :active="request()->routeIs('attendance*')">
                                    <div class="flex">
                                        <x-filament::icon icon="icon-calendar-check-2"
                                            class="w-5 h-5 me-3 text-theme dark:text-darkTheme" />
                                        Attendances
                                    </div>
                                </x-nav-link>
                            </li>

                            <li>
                                <x-nav-link wire:navigate :href="route('salary.index')" :active="request()->routeIs('salary*')">
                                    <div class="flex">
                                        <x-filament::icon icon="heroicon-o-banknotes"
                                            class="w-5 h-5 me-3 text-theme dark:text-darkTheme" />
                                        Salaries
                                    </div>
                                </x-nav-link>
                            </li>
                        </ul>
                    </li>
                @endhrd

            </ul>
        </div>
    </div>

    <div class="hidden lg:block">
        <ul>
            <li class="mb-3">
                <x-theme class="cursor-default" placement="top-end" />
            </li>
            <li>
                <x-filament::dropdown placement="top-end">
                    <x-slot name="trigger">
                        <x-filament::button icon-position="after" icon="heroicon-o-chevron-up" outlined
                            class="w-full cursor-default auth-dropdown">
                            {{ Str::words(auth()->user()->name, 2, '') }}
                            <p class="text-xs">{{ auth()->user()->email }}</p>
                        </x-filament::button>
                    </x-slot>


                    <x-filament::dropdown.list class="auth-dropdown-list">

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
