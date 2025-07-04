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

                @if (Auth::user()->is_admin)
                    {{-- Employees Qr Code --}}
                    <li>
                        <x-nav-link :href="route('admin.employees-qr-code')" :active="request()->routeIs('admin.employees-qr-code*')">
                            <div class="flex">
                                <x-filament::icon icon="icon-qr-code"
                                    class="me-3 h-5 w-5 text-theme dark:text-darkTheme" />
                                Employees Qr Code
                            </div>
                        </x-nav-link>
                    </li>
                    {{-- -------------------------------------- --}}
                @else
                    {{-- Dashboard --}}
                    <li>
                        <x-nav-link :href="Auth::user()->is_hrd ? route('hrd.dashboard') : route('user.dashboard')" :active="Auth::user()->is_hrd
                            ? request()->routeIs('hrd.dashboard*')
                            : request()->routeIs('user.dashboard*')">
                            <div class="flex">
                                <x-filament::icon icon="heroicon-o-home"
                                    class="me-3 h-5 w-5 text-theme dark:text-darkTheme" />
                                Dashboard
                            </div>
                        </x-nav-link>
                    </li>
                    {{-- ------------------------------------------ --}}

                    {{-- App --}}
                    <li>
                        <ul class="space-y-2">
                            <p class="mb-3 mt-5 text-xs text-secondary dark:text-darkSecondary">App</p>
                            <li>
                                <x-nav-link :href="Auth::user()->is_hrd
                                    ? route('hrd.daily-absent')
                                    : route('user.daily-absent')" :active="Auth::user()->is_hrd
                                    ? request()->routeIs('hrd.daily-absent*')
                                    : request()->routeIs('user.daily-absent*')">
                                    <div class="flex">
                                        <x-filament::icon icon="icon-scan-qr-code"
                                            class="me-3 h-5 w-5 text-theme dark:text-darkTheme" />
                                        Daily Absent
                                    </div>
                                </x-nav-link>
                            </li>
                            <li>
                                <x-nav-link :href="Auth::user()->is_hrd ? route('hrd.salaries') : route('user.salaries')" :active="Auth::user()->is_hrd
                                    ? request()->routeIs('hrd.salaries*')
                                    : request()->routeIs('user.salaries*')">
                                    <div class="flex">
                                        <x-filament::icon icon="heroicon-o-banknotes"
                                            class="me-3 h-5 w-5 text-theme dark:text-darkTheme" />
                                        Salaries
                                    </div>
                                </x-nav-link>
                            </li>
                        </ul>
                    </li>
                    {{-- ------------------------------------------ --}}

                    {{-- Resources --}}
                    @if (!Auth::user()->is_admin)
                        <li>
                            <ul class="space-y-2">
                                <p class="mb-3 mt-5 text-xs text-secondary dark:text-darkSecondary">Resources</p>

                                @if (Auth::user()->is_hrd)
                                    {{-- Divisions --}}
                                    <li>
                                        <x-nav-link :href="route('hrd.division.index')" :active="request()->routeIs('hrd.division.index*')">
                                            <div class="flex">
                                                <x-filament::icon icon="heroicon-o-user-group"
                                                    class="me-3 h-5 w-5 text-theme dark:text-darkTheme" />
                                                Divisions
                                            </div>
                                            <x-filament::badge class="sidebar-badge">
                                                {{ $this->divisionCount }}
                                            </x-filament::badge>
                                        </x-nav-link>
                                    </li>
                                    {{-- Employees --}}
                                    <li>
                                        <x-nav-link :href="route('hrd.employee.index')" :active="request()->routeIs('hrd.employee.index*')">
                                            <div class="flex">
                                                <x-filament::icon icon="heroicon-o-user"
                                                    class="me-3 h-5 w-5 text-theme dark:text-darkTheme" />
                                                Employees
                                            </div>
                                            <x-filament::badge class="sidebar-badge">
                                                {{ $this->userCount }}
                                            </x-filament::badge>
                                        </x-nav-link>
                                    </li>
                                @endif
                                {{-- Attendances --}}
                                <li>
                                    <x-nav-link :href="Auth::user()->is_hrd
                                        ? route('hrd.attendance.index')
                                        : route('user.attendance.index')" :active="Auth::user()->is_hrd
                                        ? request()->routeIs('hrd.attendance.index*')
                                        : request()->routeIs('user.attendance.index*')">
                                        <div class="flex">
                                            <x-filament::icon icon="icon-calendar-check-2"
                                                class="me-3 h-5 w-5 text-theme dark:text-darkTheme" />
                                            Attendances
                                        </div>
                                    </x-nav-link>
                                </li>
                            </ul>
                        </li>
                    @endif
                    {{-- ------------------------------- --}}
                @endif

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
                            class="auth-dropdown w-full cursor-default">
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
