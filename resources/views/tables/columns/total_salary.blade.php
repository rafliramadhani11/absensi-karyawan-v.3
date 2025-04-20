@php
    use App\Models\Attendance;

    $userId = $getRecord()->id;
    $monthFilter = $this->getTableFilterState('date')['month'] ?? now()->month;
    $yearFilter = $this->getTableFilterState('date')['year'] ?? now()->year;

    $attendanceCounts = Attendance::selectRaw('status, COUNT(*) as total')
        ->where('user_id', $userId)
        ->whereMonth('created_at', $monthFilter)
        ->whereYear('created_at', $yearFilter)
        ->groupBy('status')
        ->pluck('total', 'status');

    $hadir = $attendanceCounts['hadir'] ?? 0;
    $izin = $attendanceCounts['izin'] ?? 0;
    $tidakHadir = $attendanceCounts['tidak hadir'] ?? 0;

    $hadirSalary = 200000 * $hadir;
    $izinSalary = 10000 * $izin;
    $tidakHadirSalary = 20000 * $tidakHadir;

    $totalSalary = $hadirSalary - $izinSalary - $tidakHadirSalary;
@endphp

<div class="ms-3">
    Rp. {{ number_format($totalSalary, 0, ',', '.') }}
</div>
