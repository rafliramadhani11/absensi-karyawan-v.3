@section('title', 'Create New User')

<x-app-layout>
    {{-- Breadcrumb --}}
    <ul class="flex text-sm gap-x-3 text-secondary dark:text-darkSecondary">
        <li>
            <a href="{{ route('user.index') }}" class="hover:text-zinc-200">Users</a>
        </li>
        >
        <li>
            Create
        </li>

    </ul>

    {{-- Heading --}}
    <div class="flex items-center justify-between">
        <h1 class="mt-3 text-3xl font-semibold">
            Create New User
        </h1>
    </div>


    <livewire:user.user-create />
</x-app-layout>
