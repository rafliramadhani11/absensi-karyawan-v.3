<?php

use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Illuminate\Http\Request;
use Livewire\Volt\Component;
use Filament\Infolists\Infolist;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Support\Enums\Alignment;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Forms\Components\Actions\Action;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Components\Section as FormSection;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Actions\EditAction;
use App\Actions\ResetStars;
use Filament\Infolists\Components\Grid as ComponentsGrid;

new class extends Component implements HasForms, HasInfolists {
    use InteractsWithForms, InteractsWithInfolists;
    public ?array $data = [];
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
                                ->required(),
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
                    ->collapsible(),

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
                    ])
            ]);
    }

    public function placeholder()
    {
        return view('skeleton.loading');
    }
}; ?>


<div class="flex flex-col-reverse mt-5 gap-y-6 xl:grid xl:grid-cols-3 xl:gap-x-6">
    <div class="xl:col-span-2">
        {{ $this->form }}
    </div>

    <div class="xl:col-span-1">
        {{ $this->userInfoList }}
    </div>

    <x-filament-actions::modals />
</div>