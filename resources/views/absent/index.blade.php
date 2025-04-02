@section('title', 'Qr Absent')

<x-app-layout>
    {{-- Breadcrumb --}}
    {{-- <ul class="flex text-sm gap-x-3 text-secondary dark:text-darkSecondary">
        <li>
            <a href="{{ route('absent.index') }}" class="hover:text-zinc-800 dark:hover:text-zinc-200">Absents</a>
        </li>
        >
        <li>List</li>
    </ul> --}}

    {{-- Heading --}}
    <div class="flex items-center justify-between">
        <h1 class="mt-3 text-2xl font-semibold sm:text-3xl">
            Absent Daily
        </h1>
        {{-- <livewire:attendance.attendance-create /> --}}
    </div>



    {{-- <livewire:attendance.attendance-table lazy /> --}}
</x-app-layout>
