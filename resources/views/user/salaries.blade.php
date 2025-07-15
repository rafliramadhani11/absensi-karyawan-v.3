@section('title', 'Salaries')

<x-app-layout>
    {{-- Breadcrumb --}}
    <ul class="flex gap-x-3 text-sm text-secondary dark:text-darkSecondary">
        <li>
            <a href="{{ route('user.salaries') }}" class="hover:text-zinc-800 dark:hover:text-zinc-200">Salaries</a>
        </li>
        >
        <li>List</li>
    </ul>

    {{-- Heading --}}
    <div class="flex items-center justify-between">
        <h1 class="mt-3 text-2xl font-semibold sm:text-3xl">
            Salary List
        </h1>
    </div>
    <livewire:user.salary-table />
</x-app-layout>
