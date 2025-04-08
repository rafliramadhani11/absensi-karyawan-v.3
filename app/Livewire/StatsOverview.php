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
        $fromDate = $this->fromDate ?? now()->startOfWeek();
        $toDate = $this->toDate ?? now()->endOfWeek();

        $divisionChart = Trend::query(
            Division::query()
        )
            ->between(start: $fromDate, end: $toDate)
            ->perDay()
            ->count();

        $userChart = Trend::query(
            User::query()
        )
            ->between(start: $fromDate, end: $toDate)
            ->perDay()
            ->count();

        $attendanceChart = Trend::query(
            Attendance::query()
        )
            ->dateColumn('date')
            ->between(start: $fromDate, end: $toDate)
            ->perDay()
            ->count();

        return [
            Stat::make(
                'Total Division',
                Division::query()
                    ->whereBetween('created_at', [$fromDate, $toDate])
                    ->count()
            )
                ->description('Daily data grouped by week')
                ->chart($divisionChart->map(fn(TrendValue $value) => $value->aggregate)->toArray())
                ->color('primary'),

            Stat::make(
                'Total Employee',
                User::query()
                    ->withoutAdmin()
                    ->whereBetween('created_at', [$fromDate, $toDate])
                    ->count()
            )
                ->description('Daily data grouped by week ')
                ->chart($userChart->map(fn(TrendValue $value) => $value->aggregate)->toArray())
                ->color('primary'),

            Stat::make(
                'Total Attendances',
                Attendance::query()
                    ->whereBetween('date', [$fromDate, $toDate])
                    ->count()
            )
                ->description('Daily data grouped by week ')
                ->chart($attendanceChart->map(fn(TrendValue $value) => $value->aggregate)->toArray())
                ->color('primary'),
        ];
    }
}
