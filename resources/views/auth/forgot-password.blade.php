@section('title', 'Forgot Password')

<x-guest-layout>
    <div class="mb-3">
        <p class="text-2xl font-medium dark:text-darkTheme">
            {{ __('Forgot Your Password?') }}
        </p>
        <p class="mb-4 text-xs text-gray-600 dark:text-gray-400">
            {{ __("No worries! Just enter your email address and we'll help you set a new password right away.") }}
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <livewire:auth.forgot-password />
</x-guest-layout>
