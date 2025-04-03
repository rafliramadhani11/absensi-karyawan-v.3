<?php

use App\Models\User;
use Filament\Tables\Table;
use Livewire\Volt\Component;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Infolists\Components\ViewEntry;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

new class extends Component implements HasForms, HasTable {
    use InteractsWithTable, InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->query(User::withoutAdmin())
            ->searchPlaceholder('Employee, Division ...')
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('name')
                    ->label('Employee Name')
                    ->sortable()
                    ->copyable()
                    ->searchable(),

                TextColumn::make('division.name')
                    ->copyable()
                    ->sortable()
                    ->searchable(),

                TextColumn::make('in')
                    ->view('admin.in-qr-code'),

                TextColumn::make('out')
                    ->view('admin.out-qr-code'),
            ])
            ->actions([
                ViewAction::make()
                    ->infolist([
                        ViewEntry::make('in')
                            ->label('In')
                            ->view('infolists.components.admin.in-qr-code')
                    ])
            ]);
    }
}; ?>

<div class="mt-5">
    {{ $this->table }}
</div>
