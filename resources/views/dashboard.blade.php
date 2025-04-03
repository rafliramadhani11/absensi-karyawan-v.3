@if (Auth::check() && Auth::user()->is_admin)
    @section('title', 'Qr Code Absent')
@else
    @section('title', 'Dashboard')
@endif

<x-app-layout>
    <div class="space-y-10">
        @admin
            <h1 class="mt-3 text-2xl font-semibold sm:text-3xl">
                Qr Code List
            </h1>
        @endadmin

        @hrdAndEmployee
            <livewire:filters />

            @hrd
                @livewire(App\Livewire\StatsOverview::class)
                @livewire(App\Livewire\AttendancesChart::class)
            @endhrd
        @endhrdAndEmployee

    </div>
</x-app-layout>
