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
use Filament\Infolists\Components\Grid;
use Filament\Support\Enums\MaxWidth;
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
                // ->visible(fn() => now()->between(
                //     now()->copy()->setTime(9, 0, 0),
                //     now()->copy()->setTime(10, 0, 0)
                // )),

                TextColumn::make('out')
                    ->view('admin.out-qr-code'),
                // ->visible(fn() => now()->between(
                //     now()->copy()->setTime(17, 0, 0),
                //     now()->copy()->setTime(18, 0, 0)
                // )),
            ])
            ->actions([
                ViewAction::make()
                    ->infolist([
                        Grid::make(2)
                            ->schema([
                                ViewEntry::make('in')
                                    ->label('In')
                                    ->view('infolists.components.admin.in-qr-code'),
                                // ->visible(fn() => now()->between(
                                //     now()->copy()->setTime(9, 0, 0),
                                //     now()->copy()->setTime(10, 0, 0)
                                // )),
                                ViewEntry::make('out')
                                    ->label('Out')
                                    ->view('infolists.components.admin.out-qr-code'),
                                // ->visible(fn() => now()->between(
                                //     now()->copy()->setTime(17, 0, 0),
                                //     now()->copy()->setTime(20, 0, 0)
                                // )),
                            ])
                    ])->modalHeading(fn($record) => 'Detail Qr Code ' . $record->name)
                    ->modalWidth(MaxWidth::ExtraLarge),
            ]);
    }
}; ?>

<div class="mt-5">
    {{ $this->table }}
</div>