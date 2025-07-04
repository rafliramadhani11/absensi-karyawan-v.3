@section('title', 'Detail User')

<x-app-layout>
    {{-- Breadcrumb --}}
    <ul class="flex gap-x-3 text-sm text-secondary dark:text-darkSecondary">
        <li>
            <a href="{{ route('hrd.employee.index') }}" class="hover:text-zinc-200">Users</a>
        </li>
        >
        <li>
            <a href="{{ route('hrd.employee.edit', $user) }}" class="hover:text-zinc-200">{{ $user->name }}</a>
        </li>
        >
        <li>Edit</li>
    </ul>

    {{-- Heading --}}
    <div class="flex items-center justify-between">
        <h1 class="mt-3 text-3xl font-semibold">
            Detail {{ $user->name }}
        </h1>

        <livewire:user.user-header-action :$user />
    </div>


    <livewire:user.user-edit :$user />
</x-app-layout>
