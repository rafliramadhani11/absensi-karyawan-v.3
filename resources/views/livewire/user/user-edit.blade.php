<?php

use Carbon\Carbon;
use Filament\Forms\Get;
use Filament\Forms\Form;
use App\Models\Attendance;
use Filament\Tables\Table;
use Livewire\Volt\Component;
use Filament\Infolists\Infolist;
use Livewire\Attributes\Computed;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Grid;
use Filament\Support\Enums\MaxWidth;
use Filament\Forms\Components\Select;
use Filament\Support\Enums\Alignment;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ActionGroup;
use Filament\Forms\Components\DatePicker;
use Filament\Infolists\Components\Section;
use Filament\Forms\Components\ToggleButtons;
use Filament\Infolists\Components\TextEntry;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Forms\Components\Section as FormSection;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Components\Grid as ComponentsGrid;

new class extends Component implements HasForms, HasInfolists, HasTable {
    use InteractsWithForms, InteractsWithInfolists, InteractsWithTable;
    public ?array $data = [];
    public $filter = "all";
    public $user;

    public function mount(): void
    {
        $this->form->fill($this->user->toArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Account Information
                FormSection::make('Personal Account')
                    ->id('accountInformation')
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'sm' => 2,
                            'xl' => 2
                        ])->schema([
                            TextInput::make('email')
                                ->required()
                                ->email(),
                            TextInput::make('phone')
                                ->mask('9999 9999 9999 99')
                                ->tel()
                                ->required(),
                        ])
                    ])
                    ->footerActions([
                        Action::make('saveChanges')
                            ->action(function (Get $get) {
                                $data = [
                                    'email' => $get('email'),
                                    'phone' => $get('phone'),
                                ];

                                $this->user->update($data);

                                Notification::make()
                                    ->success()
                                    ->title('User berhasil diperbarui')
                                    ->body('Data akun telah disimpan.')
                                    ->send();
                            })
                            ->extraAttributes([
                                'class' => 'btn-primary'
                            ]),

                    ])
                    ->footerActionsAlignment(Alignment::End)
                    ->collapsible(),

                // Personal Information
                FormSection::make('Personal Information')
                    ->id('personalInformation')
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'sm' => 2,
                            'xl' => 2
                        ])->schema([
                            TextInput::make('nik')
                                ->mask('9999 9999 9999 9999')
                                ->tel(),
                            TextInput::make('name')
                                ->required(),
                            Select::make('gender')
                                ->options([
                                    'Laki - Laki' => 'Laki - Laki',
                                    'Perempuan' => 'Perempuan',
                                ])->native(false),
                            DatePicker::make('birth_date')
                                ->native(false),
                            TextInput::make('address')
                                ->columnSpan(['md' => 2])
                        ])
                    ])
                    ->footerActions([
                        Action::make('Save Changes')
                            ->action(function (Get $get) {
                                $data = [
                                    'nik' => $get('nik'),
                                    'name' => $get('name'),
                                    'gender' => $get('gender'),
                                    'birth_date' => $get('birth_date'),
                                    'address' => $get('address'),
                                ];

                                $this->user->update($data);

                                Notification::make()
                                    ->success()
                                    ->title('User berhasil diperbarui')
                                    ->body('Data akun telah disimpan.')
                                    ->send();
                            })
                            ->extraAttributes([
                                'class' => 'btn-primary'
                            ]),
                    ])
                    ->footerActionsAlignment(Alignment::End)
                    ->collapsed(),

            ])
            ->statePath('data');
    }

    public function userInfoList(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->user)
            ->schema([
                Section::make()
                    ->schema([
                        ComponentsGrid::make([
                            'default' => 2
                        ])->schema([
                            TextEntry::make('created_at'),
                            TextEntry::make('updated_at'),
                        ])
                    ]),

                Section::make('Division Information')
                    ->schema([
                        ComponentsGrid::make([
                            'default' => 2
                        ])->schema([
                            TextEntry::make('division.name'),
                            TextEntry::make('role'),
                        ])
                    ])
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                return $this->attendances()
                    ->when($this->filter !== 'all', function ($query) {
                        return $query->where('status', $this->filter);
                    });
            })
            ->paginated([5, 8, 10, 25, 50, 100, 'all'])
            ->defaultPaginationPageOption(5)
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('No Attendances')
            ->columns([
                TextColumn::make('index')
                    ->label('#')
                    ->rowIndex(),

                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('l')
                    ->sortable()
                    ->description(fn($state) => Carbon::parse($state)->format('j F Y')),

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
                    })
                    ->description(function (Attendance $record) {
                        if ($record->status === 'izin') {
                            $url = Storage::url($record->surat_keterangan);
                            $link = "<a href='{$url}' target='_blank' class='text-xs text-white hover:underline'>Lihat Surat Izin</a>";
                            return new HtmlString($link);
                        }
                    })
                    ->icon(fn(string $state): string => match ($state) {
                        'hadir' => 'heroicon-o-check-circle',
                        'izin' => 'heroicon-o-envelope',
                        'tidak hadir' => 'heroicon-o-x-circle',
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

                    ForceDeleteAction::make()
                        ->visible()
                        ->requiresConfirmation()
                ])->icon('heroicon-o-ellipsis-horizontal')
                    ->iconButton()
            ]);
    }

    #[Computed()]
    public function attendances()
    {
        return $this->user->attendances();
    }

    #[Computed()]
    public function attendanceCounts()
    {
        return $this->user->attendances()
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


<div class="mt-10 space-y-10">
    <div class="flex flex-col-reverse mt-5 gap-y-6 xl:grid xl:grid-cols-3 xl:gap-x-6">
        <div class="xl:col-span-2">
            {{ $this->form }}
        </div>

        <div class="xl:col-span-1">
            {{ $this->userInfoList }}
        </div>
    </div>

    <div>
        <livewire:filters />

        <div class="mt-10">
            @livewire(App\Livewire\Employee\PerformanceAttendanceChart::class,
            ['user' => $this->user])
        </div>
    </div>

    <div class="space-y-10">

        <div class="flex justify-center">
            <x-filament::tabs>
                <x-filament::tabs.item
                    :active="$this->filter === 'all'"
                    wire:click="tableFilter('all')"

                    wire:loading.attr='disabled'>
                    All
                    <x-slot name="badge" class="sidebar-badge">
                        {{ $this->user->attendances()->count() }}
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

        <div>
            {{ $this->table }}
        </div>
    </div>

    <x-filament-actions::modals />

</div>