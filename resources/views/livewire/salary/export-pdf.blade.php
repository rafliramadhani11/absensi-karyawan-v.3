<?php

use Filament\Actions\Action;
use Livewire\Volt\Component;
use Filament\Forms\Components\Grid;
use Filament\Support\Enums\MaxWidth;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\DatePicker;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Actions\Concerns\InteractsWithActions;

new class extends Component implements HasForms, HasActions {
    use InteractsWithForms, InteractsWithActions;

    public function exportAction(): Action
    {
        return Action::make('export')
            // ->label('ss')
            ->color('success')
            ->icon('heroicon-o-document-arrow-down')
            ->requiresConfirmation()
            ->modalIcon('heroicon-o-document-arrow-down')
            ->modalHeading('Cetak Penggajian')
            ->modalDescription('Pilih rentang tanggal yang akan dicetak')
            ->modalWidth(MaxWidth::ExtraLarge)
            ->modalSubmitActionLabel('Export')
            ->form([
                Grid::make(2)->schema([
                    DatePicker::make('start')
                        ->label(false)
                        ->placeholder('dari tanggal')
                        ->native(false)
                        ->displayFormat('d/m/Y')
                        ->required()
                        ->default(now()->startOfYear()),

                    DatePicker::make('end')
                        ->label(false)
                        ->placeholder('sampai tanggal')
                        ->native(false)
                        ->displayFormat('d/m/Y')
                        ->required()
                        ->default(now()->endOfYear()),
                ]),
            ])
            ->action(function ($data) {
                redirect(
                    route('hrd.salaries.pdf', [
                        'start' => $data['start'],
                        'end' => $data['end'],
                    ]),
                );
            });
    }
}; ?>

<div>
    {{ $this->exportAction }}

    <x-filament-actions::modals />
</div>