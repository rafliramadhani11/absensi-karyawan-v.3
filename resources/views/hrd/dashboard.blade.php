@section('title', 'Dashboard')

<x-app-layout>
    <div class="space-y-10">

        <h1 class="mt-3 text-2xl font-semibold sm:text-3xl">
            Dashboard
        </h1>

        <livewire:filters />

        @livewire(App\Livewire\StatsOverview::class)
        @livewire(App\Livewire\AttendancesChart::class)

    </div>
</x-app-layout>
