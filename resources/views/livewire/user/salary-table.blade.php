<?php

use Carbon\Carbon;
use App\Models\Attendance;
use Filament\Tables\Table;
use Flowframe\Trend\Trend;
use Livewire\Volt\Component;
use Flowframe\Trend\TrendValue;
use Filament\Forms\Components\Grid;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;

use Filament\Tables\Grouping\Group;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\Summarizers\Range;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

new class extends Component implements HasForms, HasTable {
    use InteractsWithTable, InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Attendance::query()->where('user_id', Auth::user()->id)
            )
            ->modifyQueryUsing(function (Builder $query) {
                $query->selectRaw("
                DATE_FORMAT(created_at, '%Y-%m') as month,
                MIN(id) as id,
                COUNT(*) as total,
                SUM(CASE WHEN status = 'hadir' THEN 1 ELSE 0 END) as hadir,
                SUM(CASE WHEN status = 'izin' THEN 1 ELSE 0 END) as izin,
                SUM(CASE WHEN status = 'tidak hadir' THEN 1 ELSE 0 END) as tidak_hadir,
                (SUM(CASE WHEN status = 'hadir' THEN 1 ELSE 0 END) * 200000) as hadir_pay,
                (SUM(CASE WHEN status = 'izin' THEN 1 ELSE 0 END) * 10000) as izin_pay,
                (SUM(CASE WHEN status = 'tidak hadir' THEN 1 ELSE 0 END) * 20000) as tidak_hadir_pay,
                ((SUM(CASE WHEN status = 'hadir' THEN 1 ELSE 0 END) * 200000)
                 - (SUM(CASE WHEN status = 'izin' THEN 1 ELSE 0 END) * 10000)
                 - (SUM(CASE WHEN status = 'tidak hadir' THEN 1 ELSE 0 END) * 20000)) as total_salary
            ")
                    ->groupBy('month')
                    ->orderByRaw("STR_TO_DATE(month, '%M %Y') desc");
            })
            ->columns([
                TextColumn::make('month')
                    ->sortable()
                    ->label('Date'),

                TextColumn::make('hadir')
                    ->label('Hadir'),

                TextColumn::make('izin')
                    ->label('Izin'),

                TextColumn::make('tidak_hadir')
                    ->label('Tidak Hadir'),

                TextColumn::make('hadir_pay')
                    ->label('Bayaran Hadir')
                    ->formatStateUsing(fn($state) => 'Rp + ' . number_format($state, 0, ',', '.')),

                TextColumn::make('izin_pay')
                    ->label('Potongan Izin')
                    ->formatStateUsing(fn($state) => 'Rp - ' . number_format($state, 0, ',', '.')),

                TextColumn::make('tidak_hadir_pay')
                    ->label('Potongan Tidak Hadir')
                    ->formatStateUsing(fn($state) => 'Rp - ' . number_format($state, 0, ',', '.')),

                TextColumn::make('total_salary')
                    ->label('Total Gaji')
                    ->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 0, ',', '.')),
            ])
            ->filters([
                Filter::make('date')
                    ->form([
                        Grid::make(1)
                            ->schema([
                                Select::make('year')
                                    ->options([
                                        '2020' => '2020',
                                        '2021' => '2021',
                                        '2022' => '2022',
                                        '2023' => '2023',
                                        '2024' => '2024',
                                        '2025' => '2025',
                                        '2026' => '2026',
                                        '2027' => '2027',
                                        '2028' => '2028',
                                        '2029' => '2029',
                                        '2030' => '2030',
                                    ])
                                    ->native(false)
                                    ->default(now()->year),
                            ])
                    ])
                    ->query(function (Builder $query, array $data) {
                        if ($data['year']) {
                            $query->whereYear('created_at', $data['year']);
                        }
                    }),
            ]);
    }

    public function placeholder()
    {
        return view('skeleton.loading');
    }
}; ?>

<div class="mt-10">
    {{ $this->table }}
</div>