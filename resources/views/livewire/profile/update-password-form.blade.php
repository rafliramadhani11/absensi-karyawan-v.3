<?php

use Filament\Forms\Form;
use Livewire\Volt\Component;
use Filament\Forms\Components\Grid;
use App\Rules\EnsureCurrentPassword;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Concerns\InteractsWithForms;

new class extends Component implements HasForms {
    use InteractsWithForms;
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make([
                    'default' => 1,
                    'sm' => 2,
                    'md' => 1,
                    'xl' => 2,
                ])->schema([
                    TextInput::make('current_password')
                        ->password()
                        ->revealable()
                        ->required()
                        ->columnSpanFull()
                        ->rules([
                            new EnsureCurrentPassword
                        ]),

                    TextInput::make('password')
                        ->label('New Password')
                        ->password()
                        ->revealable()
                        ->confirmed()
                        ->required(),

                    TextInput::make('password_confirmation')
                        ->label('New Password Confirmation')
                        ->password()
                        ->revealable()
                        ->required(),
                ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $validated = $this->form->getState();
        $validated['password'] = Hash::make($validated['password']);

        auth()->user()->update([
            'password' => $validated['password']
        ]);

        Notification::make()
            ->title('Password Updated.')
            ->body('Please Sign in again with your new password')
            ->success()
            ->send();

        Auth::logout();

        $this->redirect(route('login'));
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