@php
    use App\Models\Attendance;

    $monthFilter = $this->getTableFilterState('date')['month'] ?? now()->month;
    $yearFilter = $this->getTableFilterState('date')['year'] ?? now()->year;

    $userId = $getRecord()->id;
    $total_hadir = Attendance::where('user_id', $userId)
        ->where('status', 'hadir')
        ->whereMonth('created_at', $monthFilter)
        ->whereYear('created_at', $yearFilter)
        ->count();

    $hadir_pay = $total_hadir * 200000;
@endphp
<div class="ms-3">
    Rp. + {{ number_format($hadir_pay, 0, ',', '.') }}
</div>
