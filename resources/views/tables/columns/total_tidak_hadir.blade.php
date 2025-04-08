@php
    use App\Models\Attendance;

    $monthFilter = $this->getTableFilterState('date')['month'] ?? now()->month;
    $yearFilter = $this->getTableFilterState('date')['year'] ?? now()->year;

    $userId = $getRecord()->id;
    $total_tidak_hadir = Attendance::where('user_id', $userId)
        ->where('status', 'tidak hadir')
        ->whereMonth('date', $monthFilter)
        ->whereYear('date', $yearFilter)
        ->count();
@endphp

<div class="ms-3">
    {{ $total_tidak_hadir }}
</div>
