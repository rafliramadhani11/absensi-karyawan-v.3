@section('title', 'Login')

<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-3">
        <p class="text-2xl font-medium dark:text-darkTheme">Login your account</p>
    </div>
    <livewire:auth.login-form />
</x-guest-layout>
