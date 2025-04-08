@php
    use App\Models\Attendance;

    $monthFilter = $this->getTableFilterState('date')['month'] ?? now()->month;
    $yearFilter = $this->getTableFilterState('date')['year'] ?? now()->year;

    $userId = $getRecord()->id;
    $totalTidak_hadir = Attendance::where('user_id', $userId)
        ->where('status', 'tidak hadir')
        ->whereMonth('date', $monthFilter)
        ->whereYear('date', $yearFilter)
        ->count();

    $tidakHadir_pay = $totalTidak_hadir * 20000;
@endphp
<div class="ms-3">
    Rp. - {{ number_format($tidakHadir_pay, 0, ',', '.') }}
</div>
