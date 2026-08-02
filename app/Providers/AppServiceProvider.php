<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        date_default_timezone_set(config('app.timezone', 'Asia/Jakarta'));
        \Carbon\Carbon::setLocale('id');

        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        \Livewire\Livewire::component('cashflow', \App\Livewire\Cashflow\Index::class);
        \Livewire\Livewire::component('cashflow.index', \App\Livewire\Cashflow\Index::class);
    }
}
