@section('title', 'Reset Password')

<x-guest-layout>
    <div class="mb-3">
        <h2 class="text-2xl font-medium dark:text-darkTheme">
            {{ __('Reset Your Password') }}
        </h2>
        <p class="mb-4 text-xs text-gray-600 dark:text-gray-400">
            {{ __('Your account has been verified. Please create a new password to continue.') }}
        </p>
    </div>

    <livewire:auth.reset-password />
</x-guest-layout>
