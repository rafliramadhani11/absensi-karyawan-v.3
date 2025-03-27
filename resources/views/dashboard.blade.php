@section('title', 'Dashboard')

<x-app-layout>
    <div class="space-y-10">
        <livewire:filters />

        @livewire(App\Livewire\StatsOverview::class)
        @livewire(App\Livewire\AttendancesChart::class)
    </div>
</x-app-layout>
