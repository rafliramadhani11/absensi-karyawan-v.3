@php
    $monthFilter = $this->getTableFilterState('date')['month'] ?? now()->month;
    $yearFilter = $this->getTableFilterState('date')['year'] ?? now()->year;

    $date = Carbon\Carbon::create($yearFilter, $monthFilter)->translatedFormat('F Y');
@endphp

<div class="ms-3">
    {{ $date }}
</div>
