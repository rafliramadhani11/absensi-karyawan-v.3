<?php

use App\Models\User;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Livewire\Volt\Component;
use Filament\Support\Enums\MaxWidth;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

new class extends Component implements HasTable, HasForms {
    use InteractsWithTable, InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->query(User::query()->where('is_admin', 'false'))
            ->columns([
                TextColumn::make('index')
                    ->label('#')
                    ->rowIndex()
                    ->extraAttributes([
                        'class' => 'dark:text-darkSecondary text-secondary'
                    ]),
                TextColumn::make('nik')
                    ->searchable(),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->copyable()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('gender')
                    ->label('Jenis Kelamin')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->actions([
                ActionGroup::make([
                    DeleteAction::make()
                        ->label('Archive')
                        ->icon('heroicon-o-archive-box-arrow-down')
                        ->requiresConfirmation()
                        ->modalHeading('Archive User')
                        ->modalIcon('heroicon-o-archive-box-arrow-down'),
                    ForceDeleteAction::make()
                        ->requiresConfirmation(),
                    RestoreAction::make()
                        ->requiresConfirmation(),
                ])->icon('heroicon-o-ellipsis-horizontal')
                    ->iconButton()
            ]);
    }

    public function placeholder()
    {
        return view('skeleton.loading');
    }
}; ?>

<div>
    {{ $this->table }}
</div>