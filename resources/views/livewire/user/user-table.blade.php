<?php

use App\Models\User;
use Filament\Tables\Table;
use Livewire\Volt\Component;
use Filament\Forms\Components\Grid;
use Filament\Tables\Actions\Action;
use Filament\Support\Enums\MaxWidth;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Support\Enums\IconPosition;
use Filament\Tables\Actions\ActionGroup;
use Filament\Forms\Components\DatePicker;
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
use Filament\Tables\Actions\ForceDeleteBulkAction;

new class extends Component implements HasTable, HasForms {
    use InteractsWithTable, InteractsWithForms;
    public string $filter = 'all';

    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                $query = User::withoutAdmin()->divisionNotDeleted();

                if ($this->filter === 'trashed') {
                    return $query->onlyTrashed();
                } else {
                    return $query;
                }
            })
            ->paginated([5, 8, 10, 25, 50, 100, 'all'])
            ->defaultPaginationPageOption(8)
            ->searchPlaceholder('Nik, Name, Email ...')
            ->columns([TextColumn::make('index')->label('#')->rowIndex(), TextColumn::make('nik')->copyable()->searchable(), TextColumn::make('name')->copyable()->searchable()->sortable(), TextColumn::make('email')->copyable()->searchable()->sortable(), TextColumn::make('gender')->label('Jenis Kelamin')->sortable()])
            ->defaultSort('created_at', 'desc')
            ->filters([TrashedFilter::make()])
            ->actions([
                ActionGroup::make([
                    Action::make('exportSalary')
                        ->label('Export Salary')
                        ->color('success')
                        ->icon('heroicon-o-document-arrow-down')
                        ->requiresConfirmation()
                        ->modalIcon('heroicon-o-document-arrow-down')
                        ->modalHeading('Export Salary Karyawan')
                        ->modalDescription('Pilih rentang tanggal yang akan export')
                        ->modalWidth(MaxWidth::ExtraLarge)
                        ->modalSubmitActionLabel('Export')
                        ->form([
                            Grid::make(2)->schema([
                                DatePicker::make('start')
                                    ->label(false)
                                    ->placeholder('dari tanggal')
                                    ->native(false)
                                    ->displayFormat('d/m/Y')
                                    ->required()
                                    ->default(now()->startOfYear()),

                                DatePicker::make('end')
                                    ->label(false)
                                    ->placeholder('sampai tanggal')
                                    ->native(false)
                                    ->displayFormat('d/m/Y')
                                    ->required()
                                    ->default(now()->endOfYear()),
                            ]),
                        ])
                        ->action(function ($data, $record) {
                            redirect(
                                route('hrd.user-salaries.pdf', [
                                    'user' => $record,
                                    'start' => $data['start'],
                                    'end' => $data['end'],
                                ]),
                            );
                        }),

                    Action::make('export')
                        ->label('Export Absensi')
                        ->color('success')
                        ->icon('heroicon-o-document-arrow-down')
                        ->requiresConfirmation()
                        ->modalIcon('heroicon-o-document-arrow-down')
                        ->modalHeading('Export Absensi Karyawan')
                        ->modalDescription('Pilih rentang tanggal yang akan export')
                        ->modalWidth(MaxWidth::ExtraLarge)
                        ->modalSubmitActionLabel('Export')
                        ->form([
                            Grid::make(2)->schema([
                                DatePicker::make('start')
                                    ->label(false)
                                    ->placeholder('dari tanggal')
                                    ->native(false)
                                    ->displayFormat('d/m/Y')
                                    ->required()
                                    ->default(now()->subMonth()),

                                DatePicker::make('end')->label(false)->placeholder('sampai tanggal')->native(false)->displayFormat('d/m/Y')->required()->default(now()),
                            ]),
                        ])
                        ->action(function ($data, $record) {
                            redirect(
                                route('hrd.employee.kinerja', [
                                    'user' => $record,
                                    'start' => $data['start'],
                                    'end' => $data['end'],
                                ]),
                            );
                        }),

                    Action::make('exportKinerja')
                        ->label('Export Kinerja')
                        ->color('success')
                        ->icon('heroicon-o-document-arrow-down')
                        ->requiresConfirmation()
                        ->modalIcon('heroicon-o-document-arrow-down')
                        ->modalHeading('Export Kinerja Karyawan')
                        ->modalDescription('Pilih rentang tanggal yang akan export')
                        ->modalWidth(MaxWidth::ExtraLarge)
                        ->modalSubmitActionLabel('Export')
                        ->form([
                            Grid::make(2)->schema([
                                DatePicker::make('start')
                                    ->label(false)
                                    ->placeholder('dari tanggal')
                                    ->native(false)
                                    ->displayFormat('d/m/Y')
                                    ->required()
                                    ->default(now()->subMonth()),

                                DatePicker::make('end')->label(false)->placeholder('sampai tanggal')->native(false)->displayFormat('d/m/Y')->required()->default(now()),
                            ]),
                        ])
                        ->action(function ($data, $record) {
                            redirect(
                                route('hrd.employee.kinerja-karyawan', [
                                    'user' => $record,
                                    'start' => $data['start'],
                                    'end' => $data['end'],
                                ]),
                            );
                        }),
                ])
                    ->button()
                    ->color('success')
                    ->icon('heroicon-o-ellipsis-horizontal')
                    ->label('Export Actions')
                    ->iconPosition(IconPosition::After),


                ActionGroup::make([
                    EditAction::make()->label('Detail')->icon('heroicon-o-eye')->color('info')->url(fn($record) => route('hrd.employee.edit', $record)),

                    DeleteAction::make()
                        ->icon('heroicon-o-archive-box-arrow-down')
                        ->requiresConfirmation()
                        ->modalIcon('heroicon-o-archive-box-arrow-down')
                        ->successNotification(Notification::make()->success()->title('Successfully delete user'))
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

                        ->successNotification(Notification::make()->success()->title('Succesfully delete selected user.'))
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

                            Notification::make()->success()->title('Succesfully force delete selected user.')->send();

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
            ])
            ->selectCurrentPageOnly();
    }

    public function placeholder()
    {
        return view('skeleton.loading');
    }
}; ?>

<div class="mt-5">
    {{ $this->table }}
</div>