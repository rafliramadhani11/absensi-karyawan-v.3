<?php

use Filament\Forms\Set;
use App\Models\Division;
use Illuminate\Support\Str;
use Filament\Actions\Action;
use Livewire\Volt\Component;
use Filament\Actions\CreateAction;
use Filament\Support\Enums\MaxWidth;
use Filament\Support\Enums\Alignment;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Actions\Concerns\InteractsWithActions;

new class extends Component implements HasForms, HasActions {
    use InteractsWithActions, InteractsWithForms;

    public function createAction()
    {
        return CreateAction::make()
            ->label('New Division')
            ->icon('heroicon-m-plus')
            ->size('sm')
            ->model(Division::class)
            ->form([
                TextInput::make('name')
                    ->placeholder('division name')
                    ->required()
                    ->unique()
                    ->unique('divisions', 'name')
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn(Set $set, $state) => $set('slug', Str::slug($state))),

                TextInput::make('slug')
                    ->placeholder('autogenerate from name')
                    ->required()
                    ->readOnly(),
            ])
            ->extraAttributes([
                'class' => 'btn-primary'
            ])
            ->modalSubmitActionLabel('Create')
            ->modalSubmitAction(fn($action) => $action->extraAttributes([
                'class' => 'btn-primary',
            ]))
            ->createAnother(false)
            ->modalFooterActionsAlignment(Alignment::Center)
            ->modalWidth(MaxWidth::Large)
            ->successRedirectUrl(route('division.index'))
        ;
    }
}; ?>

<div>
    {{ $this->createAction }}

    <x-filament-actions::modals />
</div>