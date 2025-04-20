<?php

use Filament\Actions\Action;
use Livewire\Volt\Component;
use Filament\Actions\DeleteAction;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Actions\Concerns\InteractsWithActions;

new class extends Component implements HasForms, HasActions {
    use InteractsWithForms, InteractsWithActions;
    public $division;

    public function deleteAction(): Action
    {
        return Action::make('delete')
            ->color('danger')
            ->icon('heroicon-o-archive-box-arrow-down')
            ->requiresConfirmation()
            ->action(function () {
                $this->division->delete();

                Notification::make()->title('Successfully delete division')->success()->send();

                $this->redirect(route('hrd.division.index'));
            });
    }

    public function forceDeleteAction(): Action
    {
        return Action::make('forceDelete')
            ->color('danger')
            ->icon('heroicon-o-trash')
            ->requiresConfirmation()
            ->action(function () {
                $this->division->forceDelete();

                Notification::make()->title('Successfully delete permanent division')->success()->send();

                $this->redirect(route('hrd.division.index'));
            });
    }
};
?>

<div class="space-x-3">
    {{ $this->deleteAction }}
    {{ $this->forceDeleteAction }}

    <x-filament-actions::modals />
</div>
