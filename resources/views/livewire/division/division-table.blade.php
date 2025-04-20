<?php

use Filament\Forms\Set;
use App\Models\Division;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Livewire\Volt\Component;
use Filament\Support\Enums\MaxWidth;
use Filament\Support\Enums\Alignment;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Actions\BulkActionGroup;
use Illuminate\Database\Eloquent\Collection;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

new class extends Component implements HasForms, HasTable {
    use InteractsWithForms, InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(Division::query())
            ->defaultSort('created_at', 'desc')
            ->searchPlaceholder('Division name ...')
            ->columns([
                TextColumn::make('index')
                    ->label('#')
                    ->rowIndex(),

                TextColumn::make('name')
                    ->searchable()
                    ->copyable(),

                TextColumn::make('slug'),

                TextColumn::make('users_count')
                    ->label('Total Employee')
                    ->counts([
                        'users' => fn(Builder $query) => $query->where('is_admin', false),
                    ])
                    ->formatStateUsing(fn($state) => $state . ' Employee')
            ])
            ->filters([
                TrashedFilter::make()
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make()
                        ->color('info')
                        ->url(fn($record) => route('hrd.division.detail', $record)),

                    EditAction::make()
                        ->color('success')
                        ->form([
                            TextInput::make('name')
                                ->required()
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn(Set $set, $state) => $set('slug', Str::slug($state))),

                            TextInput::make('slug')
                                ->required()
                                ->readOnly(),

                        ])
                        ->using(function (Model $record, array $data): Model {
                            $record->update();

                            return $record;
                        })
                        ->modalHeading(fn($record) => 'Edit Division ' . $record->name)
                        ->modalFooterActionsAlignment(Alignment::Center)
                        ->modalWidth(MaxWidth::Large),

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
                            $this->dispatch('division-updated');
                        }),

                    ForceDeleteAction::make()
                        ->icon('heroicon-o-trash')
                        ->visible()
                        ->requiresConfirmation()
                        ->after(function () {
                            $this->dispatch('division-updated');
                        }),

                    RestoreAction::make()
                        ->requiresConfirmation()
                        ->after(function () {
                            $this->dispatch('division-updated');
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
                                ->title('Succesfully delete selected division.')
                        )
                        ->after(function () {
                            $this->dispatch('division-updated');
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
                                ->title('Succesfully force delete selected division.')
                                ->send();

                            $this->redirect(url()->previous());
                        })
                        ->after(function () {
                            $this->dispatch('division-updated');
                        }),

                    RestoreBulkAction::make()
                        ->requiresConfirmation()
                        ->after(function () {
                            $this->dispatch('division-updated');
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