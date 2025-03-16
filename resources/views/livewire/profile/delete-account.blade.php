<?php

use Filament\Actions\Action;
use Livewire\Volt\Component;
use App\Rules\EnsureCurrentPassword;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Actions\Concerns\InteractsWithActions;

new class extends Component implements HasForms, HasActions {
    use InteractsWithActions, InteractsWithForms;

    public function deleteAction(): Action
    {
        return Action::make('delete')
            ->label('Delete Account')
            ->action(function (array $data) {
                $user = auth()->user();

                Auth::logout();

                $user->delete();

                Notification::make()
                    ->title('Account Deleted.')
                    ->body('Thanks for using our application !')
                    ->success()
                    ->send();

                $this->redirect(route('login'));
            })
            ->requiresConfirmation()
            ->modalHeading('Delete Account')
            ->modalDescription('Are you sure want to delete this account ?')
            ->color('danger')
            ->form([
                TextInput::make('current_password')
                    ->label("Password")
                    ->password()
                    ->revealable()
                    ->required()
                    ->rules([
                        new EnsureCurrentPassword
                    ])->extraAttributes([
                        'class' => 'input-delete-account'
                    ])
            ])
            ->extraAttributes([
                'class' => 'w-full sm:w-fit'
            ]);
    }
}; ?>

<div class="w-full sm:w-fit">
    {{ $this->deleteAction }}

    <x-filament-actions::modals />
</div>