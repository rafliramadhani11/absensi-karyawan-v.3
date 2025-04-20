<?php

use Carbon\Carbon;
use Filament\Forms\Get;
use App\Models\Attendance;
use Filament\Tables\Table;
use Livewire\Volt\Component;
use Livewire\Attributes\Computed;
use Filament\Forms\Components\Grid;
use Livewire\Attributes\Renderless;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Support\Facades\Auth;
use Filament\Support\Enums\Alignment;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ActionGroup;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Actions\DeleteAction;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

new class extends Component implements HasTable, HasForms {
    use InteractsWithTable, InteractsWithForms;
    public $filter = "all";

    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                return $this->attendances()
                    ->when($this->filter !== 'all', function ($query) {
                        return $query->where('status', $this->filter);
                    });
            })
            ->searchPlaceholder('Employee Name ...')
            ->paginated([5, 8, 10, 25, 50, 100, 'all'])
            ->defaultSort('created_at', 'desc')
            ->defaultPaginationPageOption(8)
            ->columns([
                TextColumn::make('index')
                    ->label('#')
                    ->rowIndex(),

                TextColumn::make('created_at')
                    ->label('Date')
                    ->date('l')
                    ->sortable()
                    ->description(fn($state) => Carbon::parse($state)->format('j F Y')),

                TextColumn::make('user.name')
                    ->label('Employee')
                    ->sortable()
                    ->searchable()
                    ->visible(Auth::user()->is_hrd),

                TextColumn::make('absen_datang')
                    ->label('in')
                    ->formatStateUsing(
                        fn($state) => Carbon::parse($state)->translatedFormat('H:i')
                    ),

                TextColumn::make('absen_pulang')
                    ->label('out')
                    ->formatStateUsing(
                        fn($state) => Carbon::parse($state)->translatedFormat('H:i')
                    ),

                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn($state) => ucwords($state))
                    ->color(fn(string $state): string => match ($state) {
                        'hadir' => 'success',
                        'izin' => 'warning',
                        'tidak hadir' => 'danger',
                        'proses' => 'gray'
                    })
                    ->icon(fn(string $state): string => match ($state) {
                        'hadir' => 'heroicon-o-check-circle',
                        'izin' => 'heroicon-o-envelope',
                        'tidak hadir' => 'heroicon-o-x-circle',
                        'proses' => 'icon-timer',
                    })
                    ->sortable(),
            ])
            ->actions([
                ActionGroup::make([
                    EditAction::make()
                        ->label('Detail')
                        ->color('info')
                        ->icon('heroicon-o-eye')
                        ->form([
                            Grid::make(['xl' => 2])
                                ->schema([
                                    DatePicker::make('created_at')
                                        ->label('Date')
                                        ->displayFormat('j F Y')
                                        ->required()
                                        ->native(false)
                                        ->columnSpan(2)
                                        ->disabledDates(function ($record) {
                                            $userId = $record?->user_id;

                                            return Attendance::where('user_id', $userId)
                                                ->pluck('created_at')
                                                ->map(fn($date) => Carbon::parse($date)->toDateString())
                                                ->toArray();
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
                        ->modalHeading(fn($record) => 'Detail Attendance ' . $record->user->name)
                        ->modalFooterActionsAlignment(Alignment::Center)
                        ->using(function (Model $record, array $data): Model {
                            $updateData = [
                                'created_at' => $data['created_at'],
                                'status' => $data['status'],
                                'alasan' => null,
                                'absen_datang' => $data['absen_datang'] ?? null,
                                'absen_pulang' => $data['absen_pulang'] ?? null,
                            ];

                            if (in_array($data['status'], ['izin', 'tidak hadir'])) {
                                $updateData['alasan'] = $data['alasan'];
                                $updateData['absen_datang'] = null;
                                $updateData['absen_pulang'] = null;
                            }

                            $record->update($updateData);
                            return $record;
                        })
                        ->modalWidth(MaxWidth::Large),

                    DeleteAction::make()
                        ->icon('heroicon-o-trash')
                        ->visible()
                        ->requiresConfirmation()

                ])->icon('heroicon-o-ellipsis-horizontal')
                    ->iconButton()
            ]);
    }

    #[Computed()]
    public function attendances()
    {
        return Auth::user()->is_hrd
            ? Attendance::query()
            : Attendance::query()->where('user_id', Auth::user()->id);
    }

    #[Computed()]
    public function attendanceCounts()
    {
        return $this->attendances()
            ->selectRaw("status, COUNT(*) as total")
            ->groupBy('status')
            ->pluck('total', 'status');
    }

    public function tableFilter($filter)
    {
        $this->filter = $filter;
    }

    public function placeholder()
    {
        return view('skeleton.loading');
    }
}; ?>

<div class="mt-5">
    <div class="flex justify-center ">
        <x-filament::tabs>
            <x-filament::tabs.item
                :active="$this->filter === 'all'"
                wire:click="tableFilter('all')"

                wire:loading.attr='disabled'>
                All
                <x-slot name="badge" class="sidebar-badge">
                    {{ $this->attendances->count() }}
                </x-slot>
            </x-filament::tabs.item>

            <x-filament::tabs.item
                :active="$filter === 'hadir'"
                wire:click="tableFilter('hadir')"

                wire:loading.attr='disabled'>
                Hadir
                <x-slot name="badge">
                    {{ $this->attendanceCounts['hadir'] ?? 0 }}
                </x-slot>
            </x-filament::tabs.item>

            <x-filament::tabs.item
                :active="$filter === 'izin'"
                wire:click="tableFilter('izin')"

                wire:loading.attr='disabled'>
                Izin
                <x-slot name="badge">
                    {{ $this->attendanceCounts['izin'] ?? 0 }}
                </x-slot>
            </x-filament::tabs.item>

            <x-filament::tabs.item
                :active="$filter === 'tidak hadir'"
                wire:click="tableFilter('tidak hadir')"

                wire:loading.attr='disabled'>
                Tidak Hadir
                <x-slot name="badge">
                    {{ $this->attendanceCounts['tidak hadir'] ?? 0 }}
                </x-slot>
            </x-filament::tabs.item>

        </x-filament::tabs>
    </div>

    <div class="mt-10">
        {{ $this->table }}
    </div>
</div>