<?php

use Filament\Forms\Form;
use Livewire\Volt\Component;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;

new class extends Component implements HasForms {
    use InteractsWithForms;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(auth()->user()->toArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'sm' => 2,
                            'md' => 1,
                            'xl' => 2
                        ])
                            ->schema([
                                TextInput::make('nik')
                                    ->mask('9999 9999 9999 9999')
                                    ->tel()
                                    ->required(),

                                TextInput::make('name')
                                    ->required(),

                                Select::make('gender')
                                    ->options([
                                        'Laki - Laki' => 'Laki - Laki',
                                        'Perempuan' => 'Perempuan',
                                    ])
                                    ->native(false)
                                    ->required(),

                                DatePicker::make('birth_date')
                                    ->native(false)
                                    ->required(),

                                TextInput::make('address')
                                    ->columnSpan(['md' => 2])
                                    ->required()
                            ]),
                    ])
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $validated = $this->form->getState();
        auth()->user()->update($validated);

        Notification::make()
            ->duration(10000)
            ->title('Saved successfully')
            ->success()
            ->send();
    }
}; ?>

<div class="w-full">
    <form wire:submit="save">
        {{ $this->form }}

        <div class="flex justify-end mt-5">
            <x-primary-button type="submit" class="w-full sm:w-fit">
                Save Changes
            </x-primary-button>
        </div>
    </form>

    <x-filament-actions::modals />
</div>