<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Filament\Forms\Form;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Illuminate\Validation\ValidationException;
use Filament\Forms\Concerns\InteractsWithForms;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;

class LoginForm extends Component implements HasForms
{
    use InteractsWithForms, WithRateLimiting;

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

                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->revealable()
                    ->required()
            ])
            ->statePath('data');
    }

    public function login()
    {
        // $this->ensureIsNotRateLimited();

        $validated = $this->form->getState();

        if (Auth::attempt($validated)) {
            session()->regenerate();

            if (Auth::user()->is_admin) {
                return redirect()->intended(route('admin.employees-qr-code'));
            } elseif (Auth::user()->is_hrd) {
                return redirect()->intended(route('hrd.dashboard'));
            } else {
                return redirect()->intended(route('user.dashboard'));
            }
        }

        throw ValidationException::withMessages([
            'email' => "The provided credentials do not match our records.",
        ]);

        $this->redirect(url()->previous(), true);
    }

    public function ensureIsNotRateLimited()
    {
        try {
            $this->rateLimit(3, 30);
        } catch (TooManyRequestsException $exception) {
            throw ValidationException::withMessages([
                'email' => "Slow down! Please wait another {$exception->secondsUntilAvailable} seconds to log in.",
            ]);
        }
    }

    public function render()
    {
        return view('livewire.auth.login-form');
    }
}
