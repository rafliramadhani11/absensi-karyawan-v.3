@section('title', 'Attendances')

<x-app-layout>
    {{-- Breadcrumb --}}
    <ul class="flex gap-x-3 text-sm text-secondary dark:text-darkSecondary">
        <li>
            <a href="{{ route('hrd.attendance.index') }}"
                class="hover:text-zinc-800 dark:hover:text-zinc-200">Attendances</a>
        </li>
        >
        <li>List</li>
    </ul>

    {{-- Heading --}}
    <div class="flex items-center justify-between">
        <h1 class="mt-3 text-2xl font-semibold sm:text-3xl">
            Attendance List
        </h1>
    </div>

    <livewire:attendance.attendance-table lazy />
</x-app-layout>
