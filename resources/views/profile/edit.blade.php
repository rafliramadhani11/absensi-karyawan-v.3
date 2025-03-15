@section('title', 'Profile')

<x-app-layout>

    <div>
        <div class="flex items-center gap-x-3">
            <x-filament::icon icon="heroicon-o-user" class='size-5' />
            <h2 class="text-base font-normal text-secondary dark:text-darkSecondary">
                {{ __('Profile Settings') }}
            </h2>
        </div>
        <hr class="mt-5 border-zinc-300 dark:border-zinc-700">
    </div>

    <div class="py-2">
        <div class="space-y-6 sm:space-y-3">
            {{-- Personal Information --}}
            <div class="grid-cols-4 space-y-5 py-4 md:grid md:gap-x-6 md:space-y-0">
                <div class="col-span-2 flex flex-col justify-start">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        {{ __('Personal Information') }}
                    </h2>

                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {{ __("Update your account's personal information and email address.") }}
                    </p>
                </div>

                <div class="col-span-2 flex justify-center">
                    <livewire:profile.update-personal-form />
                </div>
            </div>

            {{-- Password Update --}}
            <div class="grid-cols-4 space-y-5 py-4 md:grid md:gap-x-6 md:space-y-0">

                <div class="col-span-2 flex flex-col justify-start">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        {{ __('Update Password') }}
                    </h2>

                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {{ __('Ensure your account is using a long, random password to stay secure.') }}
                    </p>
                </div>

                <div class="col-span-2 flex justify-center">
                    <livewire:profile.update-password-form />
                </div>

            </div>

            {{-- Account Delete --}}
            <div class="items-end justify-end py-4 md:flex">

                <div class="rounded-xl border-2 border-red-600 p-4 dark:border-red-700 md:max-w-lg xl:max-w-xl">
                    <h2 class="text-xl font-medium text-red-700">
                        {{ __('Delete Account') }}
                    </h2>

                    <p class="mt-1 text-sm text-secondary dark:text-darkSecondary">
                        {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain. ') }}
                    </p>

                    <div class="mt-5">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- <div>
        <x-slot name="header">
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                {{ __('Profile') }}
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="mx-auto space-y-6 max-w-7xl sm:px-6 lg:px-8">
                <div class="p-4 bg-white shadow sm:p-8 dark:bg-gray-800 sm:rounded-lg">
                    <div class="max-w-xl">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <div class="p-4 bg-white shadow sm:p-8 dark:bg-gray-800 sm:rounded-lg">
                    <div class="max-w-xl">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                <div class="p-4 bg-white shadow sm:p-8 dark:bg-gray-800 sm:rounded-lg">
                    <div class="max-w-xl">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
</x-app-layout>
