<?php

use App\Models\User;
use Filament\Actions\Action;
use Filament\Tables\Table;
use Livewire\Volt\Component;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Concerns\InteractsWithTable;

new class extends Component implements HasTable, HasForms {
    use InteractsWithTable, InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table->query(User::query()->where('email', '!=', 'admin@mail.com'))->columns([TextColumn::make('name')->sortable(), TextColumn::make('email')->sortable()]);
        // ->actions([
        //     DeleteAction::make()
        //         ->requiresConfirmation()
        //         ->button()
        // ]);
    }

    public function placeholder()
    {
        return view('skeleton.loading');
    }
}; ?>

<div>
    {{ $this->table }}
</div>
