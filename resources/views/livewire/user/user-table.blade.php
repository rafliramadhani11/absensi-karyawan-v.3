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
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\EditAction;
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
            ->columns([
                TextColumn::make('index')
                    ->label('#')
                    ->rowIndex(),
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
                    ->searchable()
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([TrashedFilter::make()])
            ->actions([
                ActionGroup::make([
                    EditAction::make()
                        ->label('Detail')
                        ->icon('heroicon-o-eye')
                        ->color('info')
                        ->url(fn($record) => route('user.edit', $record)),
                    DeleteAction::make()
                        ->label('Archive')
                        ->icon('heroicon-o-archive-box-arrow-down')
                        ->requiresConfirmation()
                        ->modalHeading('Archive User')
                        ->modalIcon('heroicon-o-archive-box-arrow-down')
                        ->successNotification(Notification::make()->success()->title('User berhasil di archive')),
                    ForceDeleteAction::make()
                        ->icon('heroicon-o-trash')
                        ->visible()
                        ->requiresConfirmation(),
                    RestoreAction::make()
                        ->requiresConfirmation(),
                ])
                    ->icon('heroicon-o-ellipsis-horizontal')
                    ->iconButton(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('Archive yang dipilih')
                        ->icon('heroicon-o-archive-box-arrow-down')
                        ->requiresConfirmation()
                        ->modalHeading('Archive User')
                        ->modalIcon('heroicon-o-archive-box-arrow-down')
                        ->successNotification(Notification::make()->success()->title('User berhasil di archive')),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }



    public function placeholder()
    {
        return view('skeleton.loading');
    }
}; ?>

<div class="mt-5">
    {{ $this->table }}
</div>