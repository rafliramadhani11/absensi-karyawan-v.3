@php
    $monthFilter = $this->getTableFilterState('date')['month'] ?? now()->month;
    $yearFilter = $this->getTableFilterState('date')['year'] ?? now()->year;

    $date = Carbon\Carbon::create($yearFilter, $monthFilter)->translatedFormat('Y-m');

    // dd($getRecord());

@endphp

<div class="my-5 ms-3">
    {{ $date }}
</div>
