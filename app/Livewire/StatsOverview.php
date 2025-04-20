<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Division;
use App\Models\Attendance;
use Flowframe\Trend\Trend;
use Livewire\Attributes\On;
use Flowframe\Trend\TrendValue;
use Livewire\Attributes\Computed;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsOverview extends BaseWidget
{
    public ?Carbon $fromDate;
    public ?Carbon $toDate;

    #[On('updateFromDate')]
    public function updateFromDate(?string $from): void
    {
        $this->fromDate = Carbon::parse($from);
    }

    #[On('updateToDate')]
    public function updateToDate(?string $to): void
    {
        $this->toDate = Carbon::parse($to);
    }

    protected function getStats(): array
    {
        $fromDate = $this->fromDate ?? now()->startOfYear();
        $toDate = $this->toDate ?? now()->endOfYear();

        $divisionChart = Trend::query(
            Division::query()
        )
            ->between(start: $fromDate, end: $toDate)
            ->perMonth()
            ->count();

        $userChart = Trend::query(
            User::query()
        )
            ->between(start: $fromDate, end: $toDate)
            ->perMonth()
            ->count();

        $attendanceChart = Trend::query(
            Attendance::query()
        )
            ->between(start: $fromDate, end: $toDate)
            ->perMonth()
            ->count();

        return [
            Stat::make(
                'Total Division in year',
                Division::query()
                    ->whereBetween('created_at', [$fromDate, $toDate])
                    ->count()
            )
                ->description('Data grouped per month')
                ->chart($divisionChart->map(fn(TrendValue $value) => $value->aggregate)->toArray())
                ->color('primary'),

            Stat::make(
                'Total Employee in year',
                User::query()
                    ->withoutAdmin()
                    ->whereBetween('created_at', [$fromDate, $toDate])
                    ->count()
            )
                ->description('Data grouped per month ')
                ->chart($userChart->map(fn(TrendValue $value) => $value->aggregate)->toArray())
                ->color('primary'),

            Stat::make(
                'Total Attendances in year',
                Attendance::query()
                    ->whereBetween('created_at', [$fromDate, $toDate])
                    ->count()
            )
                ->description('Data grouped per month ')
                ->chart($attendanceChart->map(fn(TrendValue $value) => $value->aggregate)->toArray())
                ->color('primary'),
        ];
    }
}
