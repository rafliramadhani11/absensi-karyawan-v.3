@php
    use App\Models\Attendance;

    $monthFilter = $this->getTableFilterState('date')['month'] ?? now()->month;
    $yearFilter = $this->getTableFilterState('date')['year'] ?? now()->year;

    $userId = $getRecord()->id;
    $total_izin = Attendance::where('user_id', $userId)
        ->where('status', 'izin')
        ->whereMonth('date', $monthFilter)
        ->whereYear('date', $yearFilter)
        ->count();

    $izin_pay = $total_izin * 10000;
@endphp
<div class="ms-3">
    Rp. - {{ number_format($izin_pay, 0, ',', '.') }}
</div>
