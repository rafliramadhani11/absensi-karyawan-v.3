<?php

namespace App\Livewire\Employee;

use Carbon\Carbon;
use App\Models\Attendance;
use Flowframe\Trend\Trend;
use Filament\Support\RawJs;
use Livewire\Attributes\On;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;

class PerformanceAttendanceChart extends ChartWidget
{
    protected static ?string $heading = 'Performance Attendance Chart';
    protected static ?string $maxHeight = '300px';
    protected static bool $isLazy = true;
    public Carbon $fromDate;
    public Carbon $toDate;
    public $user;

    #[On('updateFromDate')]
    public function updateFromDate(?string $from): void
    {
        $this->fromDate =  Carbon::parse($from);
        $this->updateChartData();
    }

    #[On('updateToDate')]
    public function updateToDate(?string $to): void
    {
        $this->toDate =  Carbon::parse($to);
        $this->updateChartData();
    }

    protected function getOptions(): RawJs
    {
        return RawJs::make(<<<JS
            {
                scales: {
                    y: {
                      beginAtZero: true,
                      ticks: {
                        stepSize: 1,
                      }
                    },
                },
            }
        JS);
    }

    protected function getData(): array
    {
        $fromDate = $this->fromDate ?? now()->startOfYear();
        $toDate = $this->toDate ?? now()->endOfYear();

        $hadir = Trend::query(
            Attendance::query()
                ->where('user_id', $this->user->id)
                ->where('status', 'hadir')
        )
            ->between(start: $fromDate, end: $toDate)
            ->perMonth()
            ->count();

        $izin = Trend::query(
            Attendance::query()
                ->where('user_id', $this->user->id)
                ->where('status', 'izin')
        )
            ->between(start: $fromDate, end: $toDate)
            ->perMonth()
            ->count();

        $tidakHadir = Trend::query(
            Attendance::query()
                ->where('user_id', $this->user->id)
                ->where('status', 'tidak hadir')
        )
            ->between(start: $fromDate, end: $toDate)
            ->perMonth()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Hadir',
                    'data' => $hadir->map(fn(TrendValue $value) => $value->aggregate),
                    'tension' => .3,
                    'borderColor' => 'rgb(34, 197, 94)',
                ],
                [
                    'label' => 'Izin',
                    'data' => $izin->map(fn(TrendValue $value) => $value->aggregate),
                    'tension' => .3,
                    'borderColor' => 'rgb(234, 179, 8)',
                ],
                [
                    'label' => 'Tidak Hadir',
                    'data' => $tidakHadir->map(fn(TrendValue $value) => $value->aggregate),
                    'tension' => .3,
                    'borderColor' => 'rgb(239, 68, 68)',
                ],
            ],
            'labels' => $hadir->map(fn(TrendValue $value) => Carbon::parse($value->date)->format('F Y')),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
