@section('title', 'Detail Division')

<x-app-layout>
    {{-- Breadcrumb --}}
    <ul class="flex gap-x-3 text-sm text-secondary dark:text-darkSecondary">
        <li>
            <a href="{{ route('division.index') }}" class="hover:text-zinc-200">Divisions</a>
        </li>
        >
        <li>
            <a href="{{ route('division.detail', $division) }}" class="hover:text-zinc-200">{{ $division->name }}</a>
        </li>
        >
        <li>Detail</li>
    </ul>

    {{-- Heading --}}
    <div class="flex items-center justify-between">
        <h1 class="mt-3 text-3xl font-semibold">
            Division {{ $division->name }}
        </h1>
    </div>


    <livewire:division.division-detail lazy :$division />
</x-app-layout>
