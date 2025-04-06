<?php


use Carbon\Carbon;
use App\Models\User;
use App\Models\Salary;
use App\Models\Attendance;
use Filament\Tables\Table;
use Flowframe\Trend\Trend;
use Livewire\Volt\Component;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Facades\DB;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

new class extends Component implements HasTable, HasForms {
    use InteractsWithTable, InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Salary::query()
            )
            ->columns([
                //
            ]);
    }
}; ?>

<div class="mt-10">
    {{ $this->table }}
</div>