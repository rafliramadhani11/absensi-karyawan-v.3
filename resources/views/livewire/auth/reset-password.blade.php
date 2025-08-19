<?php

use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Filament\Forms\Form;
use Livewire\Volt\Component;
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
                TextInput::make('password')
                    ->password()
                    ->revealable()
                    ->confirmed()
                    ->required()
                    ->rule('regex:/^(?=.*[a-zA-Z])(?=.*\d).+$/')
                    ->validationMessages([
                        'regex' => 'The password must contain both letters and numbers.',
                    ]),

                TextInput::make('password_confirmation')
                    ->password()
                    ->revealable()
                    ->required()
            ])
            ->statePath('data');
    }

    public function submit(): void
    {
        $validated = $this->form->getState();

        $token = session('reset_token');

        if (! $token) {
            $this->addError('data.password', 'Token tidak ditemukan atau sudah kadaluarsa.');
            return;
        }

        try {
            $key = env('JWT_SECRET', 'fallback_secret');
            $decoded = JWT::decode($token, new Key((string) $key, 'HS256'));
        } catch (\Exception $e) {
            $this->addError('data.password', 'Token tidak valid atau sudah kadaluarsa.');
            return;
        }

        $user = User::find($decoded->sub);
        if (! $user) {
            $this->addError('data.password', 'User tidak ditemukan.');
            return;
        }

        // Update password
        $user->password = Hash::make($validated['password']);
        $user->save();

        // Hapus token
        session()->forget('reset_token');

        Notification::make()
            ->title('Succesfully change password.')
            ->success()
            ->send();

        $this->redirect(route('login'));
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