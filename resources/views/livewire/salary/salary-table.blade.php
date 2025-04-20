<?php


use App\Models\User;
use App\Models\Attendance;
use Filament\Tables\Table;
use Livewire\Volt\Component;
use Filament\Forms\Components\Grid;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Contracts\Database\Query\Builder;


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
                    ->label('Date')
                    ->view('tables.columns.date'),

                TextColumn::make('name')
                    ->searchable()
                    ->words(2, '...')
                    ->label('Nama')
                    ->visible(auth()->user()->is_hrd),

                ViewColumn::make('total_hadir')
                    ->label('Hadir')
                    ->view('tables.columns.total_hadir'),

                ViewColumn::make('total_izin')
                    ->label('Izin')
                    ->view('tables.columns.total_izin'),

                ViewColumn::make('total_tidak_hadir')
                    ->label('Tidak Hadir')
                    ->view('tables.columns.total_tidak_hadir'),

                ViewColumn::make('hadir_pay')
                    ->label('Gaji Hadir')
                    ->view('tables.columns.hadir_pay'),

                ViewColumn::make('izin_pay')
                    ->label('Potongan Izin')
                    ->view('tables.columns.izin_pay'),

                ViewColumn::make('tidak_hadir_pay')
                    ->label('Potongan Tidak Hadir')
                    ->view('tables.columns.tidak-hadir_pay'),

                ViewColumn::make('total_salary')
                    ->label('Total Gaji')
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