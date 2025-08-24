@section('title', 'Cuti')

<x-app-layout>
    {{-- Breadcrumb --}}
    <ul class="flex gap-x-3 text-sm text-secondary dark:text-darkSecondary">
        <li>
            <a href="{{ route('user.cuti.index') }}" class="hover:text-zinc-800 dark:hover:text-zinc-200">Cuti</a>
        </li>
        >
        <li>List</li>
    </ul>

    {{-- Heading --}}
    <div class="flex items-center justify-between">
        <h1 class="mt-3 text-2xl font-semibold sm:text-3xl">
            Cuti List
        </h1>

        {{-- <livewire:user.export-pdf-action /> --}}
    </div>

    <livewire:user.cuti-table />
</x-app-layout>
