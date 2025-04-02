<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Filament\Support\Colors\Color;
use Illuminate\Support\Facades\Auth;
use Filament\Support\Enums\Alignment;
use Illuminate\Support\Facades\Blade;
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
        Blade::if('admin', function () {
            return Auth::check() && Auth::user()->is_admin;
        });

        Blade::if('hrd', function () {
            return Auth::check() && Auth::user()->is_hrd;
        });

        Blade::if('hrdAndEmployee', function () {
            return Auth::check() && !Auth::user()->is_admin;
        });



        Notifications::alignment(Alignment::End);
        FilamentColor::register([
            'primary' => Color::Zinc
        ]);
    }
}
