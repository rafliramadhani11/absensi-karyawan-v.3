<?php

use Carbon\Carbon;
use App\Models\User;
use App\Models\Salary;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\Division;
use App\Models\Attendance;
use Livewire\Volt\Component;
use Filament\Actions\CreateAction;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Grid;
use Filament\Support\Enums\MaxWidth;
use Filament\Forms\Components\Select;
use Filament\Support\Enums\Alignment;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Actions\Concerns\InteractsWithActions;

new class extends Component implements HasForms, HasActions {
    use InteractsWithForms, InteractsWithActions;

    public function createAction()
    {
        return CreateAction::make()
            ->label('New Attendance')
            ->icon('heroicon-o-plus')
            ->size('sm')
            ->model(Attendance::class)
            ->form([
                Grid::make(['xl' => 2])
                    ->schema([
                        DatePicker::make('date')
                            ->default(now()->toDateString())
                            ->label('Date')
                            ->required()
                            ->displayFormat('j F Y')
                            ->native(false)
                            ->live()
                            ->seconds(false)
                            ->formatStateUsing(fn($state) =>  Carbon::parse($state)->toDateString())
                            ->afterStateUpdated(function (Set $set) {
                                $set('user_id', null);
                            })
                            ->columnSpan(2),

                        Select::make('user_id')
                            ->label('Employee name')
                            ->options(User::withoutAdmin()->pluck('name', 'id'))
                            ->preload()
                            ->searchable()
                            ->required()
                            ->noSearchResultsMessage('The employee you are looking for has already checked in')
                            ->columnSpan(2)
                            ->disableOptionWhen(function ($value, Get $get) {
                                $date = $get('date');
                                $exists = Attendance::where('user_id', $value)
                                    ->whereDate('date', $date)
                                    ->exists();

                                return $exists;
                            }),

                        ToggleButtons::make('status')
                            ->options([
                                'hadir' => 'Hadir',
                                'izin' => 'Izin',
                                'tidak hadir' => 'Tidak Hadir',
                            ])
                            ->colors([
                                'hadir' => 'success',
                                'izin' => 'warning',
                                'tidak hadir' => 'danger',
                            ])
                            ->live()
                            ->required()
                            ->inline()
                            ->columnSpanFull(),
                    ]),
                Grid::make(['xl' => 2])
                    ->schema([
                        DateTimePicker::make('absen_datang')
                            // ->label('Absen Datang')
                            ->label('In')
                            ->date(false)
                            ->seconds(false)
                            ->native(false)
                            ->visible(
                                fn(Get $get): bool => $get('status') === 'hadir'
                            )
                            ->required(),

                        DateTimePicker::make('absen_pulang')
                            // ->label('Absen Pulang')
                            ->label('Out')
                            ->date(false)
                            ->seconds(false)
                            ->native(false)
                            ->visible(
                                fn(Get $get): bool => $get('status') === 'hadir'
                            )
                            ->required(),

                        TextInput::make('alasan')
                            ->label('Reason')
                            ->minLength(3)
                            ->required()
                            ->hidden(
                                fn(Get $get): bool => $get('status') === 'hadir'
                            )->columnSpan(['xl' => 2]),
                    ])
            ])
            ->using(function (array $data) {
                Attendance::create($data);

                // $period = Carbon::parse($data['date'])->format('Y-m');

                // // Hitung total berdasarkan period
                // $userId = $data['user_id'];
                // $hadirTotal = Attendance::where('user_id', $userId)
                //     ->where('status', 'hadir')
                //     ->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$period])
                //     ->count();

                // $izinTotal = Attendance::where('user_id', $userId)
                //     ->where('status', 'izin')
                //     ->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$period])
                //     ->count();

                // $tidakHadirTotal = Attendance::where('user_id', $userId)
                //     ->where('status', 'tidak hadir')
                //     ->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$period])
                //     ->count();

                // // Tentukan rate gaji (opsional: bisa disesuaikan dari DB atau config)
                // $hadirRate = 200000; // contoh
                // $izinRate = 10000;
                // $tidakHadirRate = 20000;

                // $hadirPay = $hadirTotal * $hadirRate;
                // $izinPay = $izinTotal * $izinRate;
                // $tidakHadirPay = $tidakHadirTotal * $tidakHadirRate;
                // $totalSalary = $hadirPay - $izinPay - $tidakHadirPay;

                // Salary::updateOrCreate(
                //     [
                //         'user_id' => $userId,
                //         'date' => $period,
                //     ],
                //     [
                //         'hadir_total' => $hadirTotal,
                //         'izin_total' => $izinTotal,
                //         'tidakHadir_total' => $tidakHadirTotal,

                //         'hadir_pay' => $hadirPay,
                //         'izin_pay' => $izinPay,
                //         'tidakHadir_pay' => $tidakHadirPay,

                //         'total_salary' => $totalSalary,
                //     ]
                // );
            })
            ->extraAttributes(['class' => 'btn-primary'])
            ->modalSubmitActionLabel('Create')
            ->modalSubmitAction(fn($action) => $action->extraAttributes([
                'class' => 'btn-primary',
            ]))
            ->createAnother(false)
            ->modalFooterActionsAlignment(Alignment::Center)
            ->modalWidth(MaxWidth::Large)
            ->successRedirectUrl(route('attendance.index'));
    }
}; ?>

<div>
    {{ $this->createAction }}

    <x-filament-actions::modals />
</div>