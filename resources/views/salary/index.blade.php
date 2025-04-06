@section('title', 'Salaries')

<x-app-layout>
    {{-- Breadcrumb --}}
    <ul class="flex gap-x-3 text-sm text-secondary dark:text-darkSecondary">
        <li>
            <a href="{{ route('salary.index') }}" class="hover:text-zinc-800 dark:hover:text-zinc-200">Salaries</a>
        </li>
        >
        <li>List</li>
    </ul>

    {{-- Heading --}}
    <div class="flex items-center justify-between">
        <h1 class="mt-3 text-2xl font-semibold sm:text-3xl">
            Salary List
        </h1>
        <x-filament::button size="sm" class="btn-primary" icon="heroicon-m-plus" :href="route('salary.create')" tag="a">
            New Salary
        </x-filament::button>
    </div>

    <livewire:salary.salary-table lazy />
</x-app-layout>
