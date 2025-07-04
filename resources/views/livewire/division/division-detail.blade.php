<?php

use App\Models\User;
use Filament\Forms\Set;
use App\Models\Division;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Livewire\Volt\Component;
use Filament\Infolists\Infolist;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Support\Enums\Alignment;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ActionGroup;
use Filament\Forms\Components\DatePicker;
use Filament\Infolists\Components\TextEntry;
use Filament\Tables\Actions\AssociateAction;
use Filament\Tables\Actions\DissociateAction;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Components\Grid as ComponentsGrid;
use Filament\Infolists\Components\Section as InfolistsSection;
use Filament\Notifications\Notification;

new class extends Component implements HasForms, HasInfolists, HasTable {
    use InteractsWithForms, InteractsWithInfolists, InteractsWithTable;
    public ?array $data = [];
    public $division;

    public function mount(): void
    {
        $this->form->fill($this->division->toArray());
    }

    public function form(Form $form): Form
    {
        return $form->schema([Section::make()->schema([Grid::make(['default' => 1, 'sm' => 2])->schema([TextInput::make('name')->placeholder('division name')->required()->unique()->unique('divisions', 'name')->live(onBlur: true)->afterStateUpdated(fn(Set $set, $state) => $set('slug', Str::slug($state))), TextInput::make('slug')->placeholder('autogenerate from name')->required()->readOnly()])])])->statePath('data');
    }

    public function divisionInfoList(Infolist $infolist): Infolist
    {
        return $infolist->record($this->division)->schema([InfolistsSection::make()->schema([ComponentsGrid::make(['default' => 2])->schema([TextEntry::make('created_at'), TextEntry::make('updated_at')])])]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->relationship(fn() => $this->division->users()->withoutAdmin())
            ->paginated([5, 8, 10, 25, 50, 100, 'all'])
            ->defaultPaginationPageOption(5)
            ->searchPlaceholder('Nik, Name, Email ...')
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('No Employees')
            ->columns([TextColumn::make('index')->label('#')->rowIndex(), TextColumn::make('nik')->copyable()->searchable(), TextColumn::make('role')->sortable(), TextColumn::make('name')->copyable()->searchable()->sortable(), TextColumn::make('email')->copyable()->searchable()->sortable(), TextColumn::make('gender')->label('Jenis Kelamin')->sortable()])
            ->actions([
                ViewAction::make()->label('Detail')->icon('heroicon-o-eye')->color('info')->url(fn($record) => route('hrd.employee.edit', $record)),
                DissociateAction::make()
                    ->label('Change')
                    ->color('success')
                    ->icon('heroicon-o-link')
                    ->form([
                        Select::make('division_id')
                            ->label('Division')
                            ->options(Division::all()->pluck('name', 'id'))
                            ->searchable(),
                    ])
                    ->using(function ($record, $data) {
                        $user = $record;
                        $user->update($data);
                    })
                    ->modalIcon('heroicon-o-link')
                    ->modalHeading('Change user division')
                    ->modalDescription(null)
                    ->modalSubmitActionLabel('Change Division')
                    ->successNotification(Notification::make()->title('Successfully change user division')->success()),
            ]);
    }

    public function placeholder()
    {
        return view('skeleton.loading');
    }
}; ?>

<div class="mt-10 space-y-10">
    <div class="flex flex-col-reverse mt-5 gap-y-6 xl:grid xl:grid-cols-3 xl:gap-x-6">
        <div class="xl:col-span-2">
            {{ $this->form }}
        </div>

        <div class="xl:col-span-1">
            {{ $this->divisionInfoList }}
        </div>
    </div>

    <div>
        {{ $this->table }}
    </div>

    <div>
        <livewire:filters />

        <div class="mt-10">
            @livewire(App\Livewire\Division\PerformanceDivisionChart::class, ['division' => $this->division])
        </div>
    </div>

    <x-filament-actions::modals />
</div>