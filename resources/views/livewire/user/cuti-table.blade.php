<?php

use Carbon\Carbon;
use App\Models\Cuti;
use Filament\Tables\Table;
use Livewire\Volt\Component;
use Livewire\Attributes\Computed;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\SelectColumn;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

new class extends Component implements HasTable, HasForms {
    use InteractsWithTable, InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->query($this->cutis())
            ->columns([
                TextColumn::make('index')
                    ->label('#')
                    ->rowIndex(),

                TextColumn::make('user.name')
                    ->label('Nama Karyawan'),

                TextColumn::make('start_date')
                    ->label('Start')
                    ->date('j F Y')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('end_date')
                    ->label('End')
                    ->date('j F Y')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('type')
                    ->badge()
                    ->formatStateUsing(fn($state) => ucwords($state))
                    ->sortable(),


                SelectColumn::make('status')
                    ->options([
                        'pending'  => '⏳ Pending',
                        'approved' => '✅ Approved',
                        'rejected'   => '❌ Reject',
                    ])
                    ->selectablePlaceholder(false)
                    ->disabled(fn() => ! Auth::user()->is_hrd),

                // TextColumn::make('status')
                //     ->badge()
                //     ->formatStateUsing(fn($state) => ucwords($state))
                //     ->color(fn(string $state): string => match ($state) {
                //         'pending' => 'warning',
                //         'approved' => 'success',
                //         'reject' => 'danger',
                //     })
                //     ->sortable(),
            ]);
    }

    #[Computed()]
    public function cutis()
    {
        return Auth::user()->is_hrd
            ? Cuti::query()
            : Cuti::query()->where('user_id', Auth::user()->id);
    }
}; ?>

<div>
    <div class="mt-10">
        {{ $this->table }}
    </div>
</div>