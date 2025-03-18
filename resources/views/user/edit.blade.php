@section('title', 'Edit User')

<x-app-layout>
    {{-- Breadcrumb --}}
    <ul class="flex gap-x-3 text-sm text-secondary dark:text-darkSecondary">
        <li>
            <a href="{{ route('user.index') }}" class="hover:text-zinc-200">Users</a>
        </li>
        >
        <li>
            <a href="{{ route('user.edit', $user) }}" class="hover:text-zinc-200">{{ $user->name }}</a>
        </li>
        >
        <li>Edit</li>
    </ul>

    {{-- Heading --}}
    <div class="flex items-center justify-between">
        <h1 class="mt-3 text-3xl font-semibold">
            Detail {{ $user->name }}
        </h1>
        {{-- <div class="flex gap-x-3">
            <x-filament::button color="danger" size="sm" icon="heroicon-m-archive-box-arrow-down" class="gap-x-3">
                Archive User
            </x-filament::button>
            <x-filament::button color="danger" size="sm" icon="heroicon-m-trash" class="gap-x-3">
                Delete Permanent
            </x-filament::button>
        </div> --}}
    </div>

    <livewire:user.user-edit :$user />
</x-app-layout>
