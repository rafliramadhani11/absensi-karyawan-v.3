<?php

namespace App\Livewire;

use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Widgets\Widget;
use Filament\Forms\Components\Grid;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;

class Filters extends Widget implements HasForms
{
    use InteractsWithForms;
    protected static string $view = 'livewire.filters';
    protected static ?int $sort = 1;
    public ?array $data = [];

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Grid::make()
                    ->schema([
                        DatePicker::make('from')
                            ->maxDate(fn(Get $get) => $get('to') ?: now())
                            ->live()
                            ->afterStateUpdated(fn(?string $state) => $this->dispatch('updateFromDate', from: $state)),

                        DatePicker::make('to')
                            ->minDate(fn(Get $get) => $get('from') ?: now())
                            ->maxDate(now())
                            ->live()
                            ->afterStateUpdated(fn(?string $state) => $this->dispatch('updateToDate', to: $state)),
                    ]),
            ]);
    }
}
