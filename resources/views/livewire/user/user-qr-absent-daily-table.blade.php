<?php

use Carbon\Carbon;
use Filament\Forms\Get;
use App\Models\Attendance;
use Filament\Tables\Table;
use Livewire\Volt\Component;
use Filament\Forms\Components\Grid;
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

new class extends Component implements HasForms, HasTable {
    use InteractsWithTable, InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                return Auth::user()->is_hrd
                    ? Attendance::query()->whereDate('created_at', now()->toDateString())
                    : Attendance::query()
                    ->where('user_id', Auth::user()->id)
                    ->whereDate('created_at', now()->toDateString());
            })
            ->paginated([5, 8, 10, 25, 50, 100, 'all'])
            ->defaultSort('created_at', 'desc')
            ->defaultPaginationPageOption(8)
            ->columns([
                TextColumn::make('index')
                    ->label('#')
                    ->rowIndex(),

                TextColumn::make('user.name')
                    ->label('Employee')
                    ->visible(Auth::user()->is_hrd),

                TextColumn::make('created_at')
                    ->label('Date')
                    ->date('l')
                    ->sortable()
                    ->description(fn($state) => Carbon::parse($state)->format('j F Y')),

                TextColumn::make('absen_datang')
                    ->label('in')
                    ->formatStateUsing(fn($state) => Carbon::parse($state)->translatedFormat('H:i')),

                TextColumn::make('absen_pulang')
                    ->label('out')
                    ->formatStateUsing(fn($state) => Carbon::parse($state)->translatedFormat('H:i')),

                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn($state) => ucwords($state))
                    ->color(
                        fn(string $state): string => match ($state) {
                            'hadir' => 'success',
                            'izin' => 'warning',
                            'tidak hadir' => 'danger',
                            'proses' => 'gray',
                        },
                    )
                    ->icon(
                        fn(string $state): string => match ($state) {
                            'hadir' => 'heroicon-o-check-circle',
                            'izin' => 'heroicon-o-envelope',
                            'tidak hadir' => 'heroicon-o-x-circle',
                            'proses' => 'icon-timer',
                        },
                    )
                    ->sortable(),
            ])
            ->actions([
                ActionGroup::make([
                    EditAction::make()
                        ->label('Detail')
                        ->color('info')
                        ->icon('heroicon-o-eye')
                        ->form([
                            Grid::make(['xl' => 2])->schema([
                                DatePicker::make('date')->label('Date')->displayFormat('j F Y')->required()->native(false)->columnSpan(2),
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
                            Grid::make(['xl' => 2])->schema([
                                DateTimePicker::make('absen_datang')
                                    // ->label('Absen Datang')
                                    ->label('In')
                                    ->date(false)
                                    ->seconds(false)
                                    ->native(false)
                                    ->visible(fn(Get $get): bool => $get('status') === 'hadir')
                                    ->required(),

                                DateTimePicker::make('absen_pulang')
                                    // ->label('Absen Pulang')
                                    ->label('Out')
                                    ->date(false)
                                    ->seconds(false)
                                    ->native(false)
                                    ->visible(fn(Get $get): bool => $get('status') === 'hadir')
                                    ->required(),

                                TextInput::make('alasan')
                                    ->label('Reason')
                                    ->minLength(3)
                                    ->required()
                                    ->hidden(fn(Get $get): bool => $get('status') === 'hadir')
                                    ->columnSpan(['xl' => 2]),
                            ]),
                        ])
                        ->modalHeading(fn($record) => 'Detail Attendance ' . $record->user->name)
                        ->modalFooterActionsAlignment(Alignment::Center)
                        ->using(function (Model $record, array $data): Model {
                            $updateData = [
                                'date' => $data['date'],
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

                    DeleteAction::make()->icon('heroicon-o-trash')->visible()->requiresConfirmation(),
                ])
                    ->icon('heroicon-o-ellipsis-horizontal')
                    ->iconButton()
                    ->visible(Auth::user()->is_hrd),
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