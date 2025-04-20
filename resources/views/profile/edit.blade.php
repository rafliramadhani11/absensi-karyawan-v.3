@section('title', 'Profile')

<x-app-layout>

    <div>
        <div class="flex items-center gap-x-3">
            <h2 class="text-base font-normal text-secondary dark:text-darkSecondary">
                {{ __('Profile Settings') }}
            </h2>
        </div>
    </div>

    <div class="py-2">
        <div class="space-y-6 sm:space-y-3">

            @if (!Auth::user()->is_admin && !Auth::user()->is_hrd)
                <div class="grid-cols-4 space-y-5 py-4 md:grid md:gap-x-6 md:space-y-0">
                    <div class="col-span-2 flex flex-col justify-start">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('Personal Account') }}
                        </h2>

                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            {{ __('Update your personal account information') }}
                        </p>
                    </div>

                    <div class="col-span-2 flex justify-center">
                        <livewire:profile.update-personal-account-form />
                    </div>
                </div>

                <div class="grid-cols-4 space-y-5 py-4 md:grid md:gap-x-6 md:space-y-0">
                    <div class="col-span-2 flex flex-col justify-start">
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('Personal Information') }}
                        </h2>

                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            {{ __('Update your personal information') }}
                        </p>
                    </div>

                    <div class="col-span-2 flex justify-center">
                        <livewire:profile.update-personal-information-form />
                    </div>
                </div>
            @endif

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

            {{-- <div class="items-end justify-end py-4 md:flex">

                <div class="p-4 border-2 border-red-600 rounded-xl dark:border-red-700 md:max-w-lg xl:max-w-xl">
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

            </div> --}}
        </div>
    </div>

</x-app-layout>
