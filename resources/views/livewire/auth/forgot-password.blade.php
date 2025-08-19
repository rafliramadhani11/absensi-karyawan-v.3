<?php

use App\Models\User;
use Firebase\JWT\JWT;
use Filament\Forms\Form;
use Livewire\Volt\Component;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
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
                TextInput::make('email')
                    ->label('Email Address')
                    ->email()
                    ->autofocus()
                    ->required(),
            ])
            ->statePath('data');
    }

    public function submit(): void
    {
        $data = $this->form->getState();
        $user = User::where('email', $data['email'])->first();

        if (! $user || $user->is_admin || $user->is_hrd) {
            $this->addError('data.email', 'The provided credentials do not match our records.');
            return;
        }

        // Payload untuk JWT
        $payload = [
            'sub' => $user->id,
            'email' => $user->email,
            'iat' => time(),
            'exp' => time() + (60 * 10),
        ];

        $jwt = JWT::encode($payload, env('JWT_SECRET', 'fallback_secret'), 'HS256');
        session(['reset_token' => $jwt]);

        // Redirect ke halaman reset password
        $this->redirect(route('password.reset'));
    }
}; ?>

<div>
    <form wire:submit="submit">
        {{ $this->form }}

        <x-primary-button type="submit" class="w-full mt-6">
            Reset Password
        </x-primary-button>
    </form>
</div>