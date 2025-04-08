<?php


use Carbon\Carbon;
use App\Models\User;
use App\Models\Attendance;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Tables\Table;
use Livewire\Volt\Component;
use Filament\Tables\Grouping\Group;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\Summarizers\Count;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Query\Builder as QueryBuilder;

new class extends Component implements HasTable, HasForms {
    use InteractsWithTable, InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                User::withoutAdmin()
            )
            ->searchPlaceholder('Employee Name ...')
            ->columns([
                ViewColumn::make('date')
                    ->view('tables.columns.date'),

                TextColumn::make('name')
                    ->searchable()
                    ->label('Nama'),

                ViewColumn::make('total_hadir')
                    ->view('tables.columns.total_hadir'),

                ViewColumn::make('total_izin')
                    ->view('tables.columns.total_izin'),

                ViewColumn::make('total_tidak_hadir')
                    ->view('tables.columns.total_tidak_hadir'),

                ViewColumn::make('hadir_pay')
                    ->view('tables.columns.hadir_pay'),

                ViewColumn::make('izin_pay')
                    ->view('tables.columns.izin_pay'),

                ViewColumn::make('tidak_hadir_pay')
                    ->view('tables.columns.tidak-hadir_pay'),

                ViewColumn::make('total_salary')
                    ->view('tables.columns.total_salary'),
            ])
            ->filters([
                Filter::make('date')
                    ->form([
                        Grid::make(1)
                            ->schema([
                                Select::make('month')
                                    ->options([
                                        '1' => 'Januari',
                                        '2' => 'Februari',
                                        '3' => 'Maret',
                                        '4' => 'April',
                                        '5' => 'Mei',
                                        '6' => 'Juni',
                                        '7' => 'Juli',
                                        '8' => 'Agustus',
                                        '9' => 'September',
                                        '10' => 'Oktober',
                                        '11' => 'November',
                                        '12' => 'Desember',
                                    ])
                                    ->native(false)
                                    ->default(now()->month),

                                Select::make('year')
                                    ->options([
                                        '2020' => '2020',
                                        '2021' => '2021',
                                        '2022' => '2022',
                                        '2023' => '2023',
                                        '2024' => '2024',
                                        '2025' => '2025',
                                        '2026' => '2026',
                                    ])
                                    ->native(false)
                                    ->default(now()->year),
                            ])
                    ])
            ])
        ;
    }

    public function placeholder()
    {
        return view('skeleton.loading');
    }
}; ?>

<div class="mt-10">
    {{ $this->table }}
</div>