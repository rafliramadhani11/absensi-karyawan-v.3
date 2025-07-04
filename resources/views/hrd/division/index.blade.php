@section('title', 'Division')

<x-app-layout>
    {{-- Breadcrumb --}}
    <ul class="flex gap-x-3 text-sm text-secondary dark:text-darkSecondary">
        <li>
            <a href="{{ route('hrd.division.index') }}" class="hover:text-zinc-200">Divisions</a>
        </li>
        >
        <li>List</li>
    </ul>

    {{-- Heading --}}
    <div class="flex items-center justify-between">
        <h1 class="mt-3 text-2xl font-semibold sm:text-3xl">
            Division List
        </h1>
        <livewire:division.division-create />
    </div>

    <livewire:division.division-table />
</x-app-layout>
