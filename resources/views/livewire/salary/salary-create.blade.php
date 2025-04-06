<?php

use App\Models\User;
use Filament\Forms\Set;
use App\Models\Division;
use Filament\Forms\Form;
use App\Models\Attendance;
use Livewire\Volt\Component;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Get;

new class extends Component implements HasForms {
    use InteractsWithForms;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make([
                    'xl' => 3
                ])
                    ->schema([
                        Section::make('Employee Absen Information')
                            ->description('Choose which employee wants to count the salary')
                            ->schema([
                                Grid::make(['xl' => 3])
                                    ->schema([
                                        Select::make('user_id')
                                            ->label('Employee')
                                            ->live()
                                            ->native(false)
                                            ->options(User::withoutAdmin()->pluck('name', 'id'))
                                            ->afterStateUpdated(function (Set $set, Get $get, $state) {
                                                $this->setSalaryData($get, $set, $state, $get('month'), $get('year'));
                                            })
                                            ->required(),

                                        Select::make('month')
                                            ->options(array_combine(range(1, 12), [
                                                'Januari',
                                                'Februari',
                                                'Maret',
                                                'April',
                                                'Mei',
                                                'Juni',
                                                'Juli',
                                                'Agustus',
                                                'September',
                                                'Oktober',
                                                'November',
                                                'Desember'
                                            ]))
                                            ->native(false)
                                            ->default(now()->month)
                                            ->live()
                                            ->afterStateUpdated(function (Get $get, Set $set, $state) {
                                                $this->setSalaryData($get, $set, $get('user_id'), $state, $get('year'));
                                            }),

                                        Select::make('year')
                                            ->options([
                                                now()->year => now()->year,
                                                now()->subYear()->year => now()->subYear()->year,
                                                now()->subYears(2)->year => now()->subYears(2)->year,
                                            ])
                                            ->live()
                                            ->afterStateUpdated(function (Get $get, Set $set, $state) {
                                                $this->setSalaryData($get, $set, $get('user_id'), $get('month'), $state);
                                            })
                                            ->native(false)
                                            ->default(now()->year),
                                    ]),


                                Grid::make(['xl' => 2])
                                    ->schema([
                                        TextInput::make('division')
                                            ->label('Division')
                                            ->readOnly()
                                            ->required(),

                                        TextInput::make('role')
                                            ->label('Position')
                                            ->readOnly()
                                            ->required()
                                    ]),

                                Grid::make([
                                    'xl' => 3
                                ])
                                    ->schema([
                                        TextInput::make('hadir')
                                            ->numeric()
                                            ->readOnly()
                                            ->required(),

                                        TextInput::make('izin')
                                            ->numeric()
                                            ->readOnly()
                                            ->required(),

                                        TextInput::make('tidak_hadir')
                                            ->numeric()
                                            ->readOnly()
                                            ->required(),
                                    ])

                            ])->columnSpan(['xl' => 2]),

                        Section::make('Daily Pay')
                            ->description('Based on position employee in division')
                            ->schema([
                                TextInput::make('hadir_pay')
                                    ->label('Hadir Pay (+)')
                                    ->required()
                                    ->prefix('Rp')
                                    ->currencyMask('.', ','),
                                TextInput::make('izin_pay')
                                    ->label('Izin Pay (-)')
                                    ->required()
                                    ->prefix('Rp')
                                    ->currencyMask('.', ','),
                                TextInput::make('tidakHadir_pay')
                                    ->label('Tidak Hadir Pay (-)')
                                    ->required()
                                    ->prefix('Rp')
                                    ->currencyMask('.', ','),


                            ])->columnSpan(['xl' => 1])
                    ])
            ])
            ->columns(3)
            ->statePath('data');
    }

    protected function setSalaryData(Get $get, Set $set, $userId, $month, $year): void
    {
        $record = User::with('division')->find($userId);


        if (!$record) {
            $set('division', null);
            $set('role', null);
            $set('hadir', null);
            $set('izin', null);
            $set('tidak_hadir', null);

            $set('hadir_pay', null);
            $set('izin_pay', null);
            $set('tidakHadir_pay', null);

            return;
        }

        $counts = Attendance::where('user_id', $userId)
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $set('division', $record->division->name ?? '-');
        $set('role', $record->role ?? '-');
        $set('hadir', $counts['hadir'] ?? 0);
        $set('izin', $counts['izin'] ?? 0);
        $set('tidak_hadir', $counts['tidak hadir'] ?? 0);


        $set('hadir_pay', $record->role === 'Kepala Divisi' ? 300000 : 200000);
        $set('izin_pay', 10000);
        $set('tidakHadir_pay', 20000);
    }

    public function create(): void
    {
        dd($this->form->getState());
    }
}; ?>

<div class="mt-10">
    <form wire:submit="create">
        {{ $this->form }}

        <div class="flex items-center justify-end mt-5">
            <x-filament::button size="sm" class="btn-primary" type="submit">
                Create Salary
            </x-filament::button>
        </div>
    </form>

    <x-filament-actions::modals />
</div>