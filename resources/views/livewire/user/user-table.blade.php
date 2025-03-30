<?php

use App\Models\User;
use Filament\Tables\Table;
use Livewire\Volt\Component;
use Filament\Tables\Actions\Action;
use Filament\Support\Enums\MaxWidth;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Actions\BulkActionGroup;
use Illuminate\Database\Eloquent\Collection;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Actions\ForceDeleteBulkAction;

new class extends Component implements HasTable, HasForms {
    use InteractsWithTable, InteractsWithForms;
    public string $filter = 'all';

    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                $query = User::withoutAdmin();

                if ($this->filter === 'trashed') {
                    return $query->onlyTrashed();
                } else {
                    return $query;
                }
            })
            ->paginated([5, 8, 10, 25, 50, 100, 'all'])
            ->defaultPaginationPageOption(8)
            ->searchPlaceholder('Nik, Name, Email ...')
            ->columns([
                TextColumn::make('index')
                    ->label('#')
                    ->rowIndex(),
                TextColumn::make('nik')
                    ->copyable()
                    ->searchable(),
                TextColumn::make('name')
                    ->copyable()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->copyable()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('gender')
                    ->label('Jenis Kelamin')
                    ->sortable()
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([TrashedFilter::make()])
            ->actions([
                ActionGroup::make([
                    EditAction::make()
                        ->label('Detail')
                        ->icon('heroicon-o-eye')
                        ->color('info')
                        ->url(fn($record) => route('user.edit', $record))
                        ->extraAttributes([
                            'wire:navigate' => true
                        ]),

                    DeleteAction::make()
                        ->icon('heroicon-o-archive-box-arrow-down')
                        ->requiresConfirmation()
                        ->modalIcon('heroicon-o-archive-box-arrow-down')
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Successfully delete user')
                        )
                        ->after(function () {
                            $this->dispatch('user-updated');
                        }),

                    ForceDeleteAction::make()
                        ->icon('heroicon-o-trash')
                        ->visible()
                        ->requiresConfirmation()
                        ->after(function () {
                            $this->dispatch('user-updated');
                        }),

                    RestoreAction::make()
                        ->requiresConfirmation()
                        ->after(function () {
                            $this->dispatch('user-updated');
                        }),
                ])
                    ->icon('heroicon-o-ellipsis-horizontal')
                    ->iconButton(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->color('danger')
                        ->icon('heroicon-o-archive-box-arrow-down')
                        ->modalIcon('heroicon-o-archive-box-arrow-down')
                        ->requiresConfirmation()

                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Succesfully delete selected user.')
                        )
                        ->after(function () {
                            $this->dispatch('user-updated');
                        }),

                    BulkAction::make('forceDelete')
                        ->color('danger')
                        ->icon('heroicon-o-trash')
                        ->visible()
                        ->requiresConfirmation()
                        ->action(function (Collection $records) {
                            $records->each->forceDelete();

                            Notification::make()
                                ->success()
                                ->title('Succesfully force delete selected user.')
                                ->send();

                            $this->redirect(url()->previous());
                        })
                        ->after(function () {
                            $this->dispatch('user-updated');
                        }),

                    RestoreBulkAction::make()
                        ->requiresConfirmation()
                        ->after(function () {
                            $this->dispatch('user-updated');
                        }),
                ]),
            ])->selectCurrentPageOnly();
    }



    public function placeholder()
    {
        return view('skeleton.loading');
    }
}; ?>

<div class="mt-5">
    {{ $this->table }}
</div>