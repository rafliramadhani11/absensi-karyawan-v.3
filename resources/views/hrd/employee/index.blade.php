@section('title', 'Users')

<x-app-layout>
    {{-- Breadcrumb --}}
    <ul class="flex gap-x-3 text-sm text-secondary dark:text-darkSecondary">
        <li>
            <a href="{{ route('hrd.employee.index') }}" class="hover:text-zinc-200">Users</a>
        </li>
        >
        <li>List</li>
    </ul>

    {{-- Heading --}}
    <div class="flex items-center justify-between">
        <h1 class="mt-3 text-2xl font-semibold sm:text-3xl">
            Employees List
        </h1>
        <div class="flex space-x-3">
            <livewire:export-pdf-action />

            <x-filament::button size="sm" class="btn-primary" icon="heroicon-m-plus" :href="route('hrd.employee.create')" tag="a">
                New Employee
            </x-filament::button>
        </div>
    </div>

    <livewire:user.user-table />
</x-app-layout>
