<?php

use Filament\Actions\Action;
use Livewire\Volt\Component;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
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

                Notification::make()
                    ->title('Successfully delete user')
                    ->success()
                    ->send();

                $this->redirect(route('user.index'));
            })
        ;
    }

    public function forceDeleteAction(): Action
    {
        return Action::make('forceDelete')
            ->color('danger')
            ->icon('heroicon-o-trash')
            ->requiresConfirmation()
            ->action(function () {
                $this->user->forceDelete();

                Notification::make()
                    ->title('Successfully delete permanent user')
                    ->success()
                    ->send();

                $this->redirect(route('user.index'));
            })
        ;
    }
}
?>

<div class="space-x-3">
    {{ $this->deleteAction }}
    {{ $this->forceDeleteAction }}

    <x-filament-actions::modals />
</div>