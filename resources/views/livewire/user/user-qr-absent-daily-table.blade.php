<?php

use Carbon\Carbon;
use App\Models\Attendance;
use Filament\Tables\Table;
use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

new class extends Component implements HasForms, HasTable {
    use InteractsWithTable, InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->query(Attendance::query()->where('user_id', Auth::id()))
            ->paginated([5, 8, 10, 25, 50, 100, 'all'])
            ->defaultSort('created_at', 'desc')
            ->defaultPaginationPageOption(8)
            ->columns([
                TextColumn::make('index')
                    ->label('#')
                    ->rowIndex(),

                TextColumn::make('created_at')
                    ->label('Date')
                    ->date('l')
                    ->sortable()
                    ->description(fn($state) => Carbon::parse($state)->format('j F Y')),

                TextColumn::make('absen_datang')
                    ->label('in')
                    ->formatStateUsing(
                        fn($state) => Carbon::parse($state)->translatedFormat('H:i')
                    ),

                TextColumn::make('absen_pulang')
                    ->label('out')
                    ->formatStateUsing(
                        fn($state) => Carbon::parse($state)->translatedFormat('H:i')
                    ),

                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn($state) => ucwords($state))
                    ->color(fn(string $state): string => match ($state) {
                        'hadir' => 'success',
                        'izin' => 'warning',
                        'tidak hadir' => 'danger',
                        'proses' => 'gray'
                    })
                    ->icon(fn(string $state): string => match ($state) {
                        'hadir' => 'heroicon-o-check-circle',
                        'izin' => 'heroicon-o-envelope',
                        'tidak hadir' => 'heroicon-o-x-circle',
                        'proses' => 'icon-timer',
                    })
                    ->sortable(),
            ]);
    }

    public function placeholder()
    {
        return view('skeleton.loading');
    }
}; ?>

<div class="mt-10">
    {{ $this->table }}
</div>
