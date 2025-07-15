<?php

use Filament\Actions\Action;
use Livewire\Volt\Component;
use Filament\Forms\Contracts\HasForms;
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
            ->url(route('user.profile-pdf', ['user' => Auth::user()]));
    }
}; ?>

<div>
    {{ $this->exportAction }}
</div>