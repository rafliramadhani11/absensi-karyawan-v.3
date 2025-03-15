<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Alignment;
use Illuminate\Support\ServiceProvider;
use Filament\Notifications\Notification;
use Illuminate\Cache\RateLimiting\Limit;
use Filament\Support\Facades\FilamentColor;
use Illuminate\Support\Facades\RateLimiter;
use Filament\Support\Enums\VerticalAlignment;
use Filament\Notifications\Livewire\Notifications;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Notifications::alignment(Alignment::End);
        Notifications::verticalAlignment(VerticalAlignment::End);

        FilamentColor::register([
            'primary' => Color::Zinc
        ]);
    }
}
