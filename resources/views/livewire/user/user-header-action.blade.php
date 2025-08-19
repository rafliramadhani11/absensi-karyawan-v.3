<?php

use Livewire\Attributes\On;
use Filament\Actions\Action;
use Livewire\Volt\Component;
use Spatie\LaravelPdf\Facades\Pdf;
use Filament\Forms\Components\Grid;
use Filament\Support\Enums\MaxWidth;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Actions\Concerns\InteractsWithActions;

new class extends Component implements HasForms, HasActions {
    use InteractsWithForms, InteractsWithActions;
    public $user;

    public function deleteAction(): Action
    {
        return Action::make('delete')
            ->color('danger')
            ->icon('heroicon-o-archive-box-arrow-down')
            ->requiresConfirmation()
            ->action(function () {
                $this->user->delete();

                Notification::make()->title('Successfully delete user')->success()->send();

                $this->redirect(route('hrd.employee.index'));
            });
    }

    public function forceDeleteAction(): Action
    {
        return Action::make('forceDelete')
            ->color('danger')
            ->icon('heroicon-o-trash')
            ->requiresConfirmation()
            ->action(function () {
                $this->user->forceDelete();

                Notification::make()->title('Successfully delete permanent user')->success()->send();

                $this->redirect(route('hrd.employee.index'));
            });
    }

    public function exportAction(): Action
    {
        return Action::make('export')
            ->label('Export Absensi')
            ->color('success')
            ->icon('heroicon-o-document-arrow-down')
            ->requiresConfirmation()
            ->modalIcon('heroicon-o-document-arrow-down')
            ->modalHeading('Cetak Kinerja Karyawan')
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
                    route('hrd.employee.kinerja', [
                        'user' => $this->user,
                        'start' => $data['start'],
                        'end' => $data['end'],
                    ]),
                );
            });
    }

    public function exportSalary(): Action
    {
        return Action::make('exportSalary')
            ->label('Export Salary')
            ->color('success')
            ->icon('heroicon-o-document-arrow-down')
            ->requiresConfirmation()
            ->modalIcon('heroicon-o-document-arrow-down')
            ->modalHeading('Export Salary Karyawan')
            ->modalDescription('Pilih rentang tanggal yang akan export')
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
                    route('hrd.user-salaries.pdf', [
                        'user' => $this->user,
                        'start' => $data['start'],
                        'end' => $data['end'],
                    ]),
                );
            });
    }
};
?>

<div class="space-x-3">
    {{ $this->exportSalary }}
    {{ $this->exportAction }}
    {{ $this->deleteAction }}
    {{ $this->forceDeleteAction }}

    <x-filament-actions::modals />
</div>
