<?php

use App\Models\User;
use Filament\Forms\Form;
use Livewire\Volt\Component;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Grid;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Wizard;
use Illuminate\Support\Facades\Blade;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Concerns\InteractsWithForms;

new class extends Component implements HasForms {
    use InteractsWithForms;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Step::make('Personal Account')
                        ->completedIcon('heroicon-m-check-circle')
                        ->schema([
                            TextInput::make('email')
                                ->placeholder('yourmail@mail.com')
                                ->unique('users', 'email')
                                ->required(),

                            Grid::make([
                                'sm' => 2
                            ])
                                ->schema([
                                    TextInput::make('password')
                                        ->password()
                                        ->revealable()
                                        ->confirmed()
                                        ->required(),

                                    TextInput::make('password_confirmation')
                                        ->password()
                                        ->revealable()
                                        ->required()
                                ])
                        ])->columns(2),

                    Step::make('Personal Information')
                        ->schema([
                            Grid::make([
                                'sm' => 2,
                                'md' => 3
                            ])->schema([
                                TextInput::make('nik')
                                    ->placeholder('9999 9999 9999 9999')
                                    ->mask('9999 9999 9999 9999')
                                    ->required()
                                    ->tel(),

                                TextInput::make('name')
                                    ->placeholder('your name')
                                    ->required(),
                            ]),

                            Grid::make([
                                'sm' => 2,
                                'md' => 3
                            ])->schema([
                                Select::make('gender')
                                    ->options([
                                        'Laki - Laki' => 'Laki - Laki',
                                        'Perempuan' => 'Perempuan',
                                    ])
                                    ->required()
                                    ->native(false),

                                DatePicker::make('birth_date')
                                    ->placeholder('your birth date')
                                    ->required()
                                    ->native(false),

                                TextInput::make('phone')
                                    ->placeholder('9999 9999 9999')
                                    ->mask('9999 9999 9999 99')
                                    ->tel()
                                    ->required(),

                                TextInput::make('address')
                                    ->placeholder('your address')
                                    ->required()
                                    ->columnSpan(['md' => 3]),
                            ]),
                        ])->columns(3)

                ])
                    ->previousAction(
                        fn(Action $action) => $action->label('Previous Step')
                            ->extraAttributes([
                                'class' => 'btn-prev-act'
                            ]),
                    )
                    ->nextAction(
                        fn(Action $action) => $action->label('Next step')
                            ->extraAttributes([
                                'class' => 'btn-primary'
                            ]),
                    )->submitAction(new HtmlString(Blade::render(<<<BLADE
                <x-primary-button type="submit" class="py-1.5">
                    Create User
                </x-primary-button>
            BLADE)))
            ])
            ->statePath('data');
    }

    public function create(): void
    {
        $validated = $this->form->getState();
        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        Notification::make()
            ->title('User created')
            ->body('Successfully create new user.')
            ->success()
            ->send();

        $this->redirect(route('user.index'));
    }
}; ?>


<div class="mt-5">

    <form wire:submit="create">
        {{ $this->form }}

        <!-- <button type="submit">
            Submit
        </button> -->
    </form>

    <x-filament-actions::modals />
</div>