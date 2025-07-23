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
use Illuminate\Support\Facades\Auth;

new class extends Component implements HasForms, HasActions {
    use InteractsWithForms, InteractsWithActions;

    public function exportAction(): Action
    {
        return Action::make('export')
            ->color('success')
            ->icon('heroicon-o-document-arrow-down')
            ->requiresConfirmation()
            ->modalIcon('heroicon-o-document-arrow-down')
            ->modalHeading('Cetak Kinerja Absensi')
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
                        ->default(now()->subMonth()),

                    DatePicker::make('end')->label(false)->placeholder('sampai tanggal')->native(false)->displayFormat('d/m/Y')->required()->default(now()),
                ]),
            ])
            ->action(function ($data) {
                redirect(
                    route('user.kinerja.export', [
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
