@section('title', 'Attendances')

<x-app-layout>
    {{-- Breadcrumb --}}
    <ul class="flex gap-x-3 text-sm text-secondary dark:text-darkSecondary">
        <li>
            <a href="{{ route('attendance.index') }}" class="hover:text-zinc-200">Attendances</a>
        </li>
        >
        <li>List</li>
    </ul>

    {{-- Heading --}}
    <div class="flex items-center justify-between">
        <h1 class="mt-3 text-3xl font-semibold">
            Attendance List
        </h1>
        {{-- <x-filament::button size="sm" class="btn-new-users" icon="heroicon-m-plus" :href="route('user.create')" tag="a">
            New User
        </x-filament::button> --}}
    </div>



    <livewire:attendance.attendance-table lazy />
</x-app-layout>
