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
        require_once app_path('helpers.php');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        date_default_timezone_set(config('app.timezone', 'Asia/Jakarta'));
        \Carbon\Carbon::setLocale('id');

        // Blade Directives for Friendly Indonesian Dates & Times
        \Illuminate\Support\Facades\Blade::directive('idDate', function ($expression) {
            return "<?php echo format_id_date($expression); ?>";
        });

        \Illuminate\Support\Facades\Blade::directive('idDateTime', function ($expression) {
            return "<?php echo format_id_datetime($expression); ?>";
        });

        \Illuminate\Support\Facades\Blade::directive('idFullDate', function ($expression) {
            return "<?php echo format_id_full_date($expression); ?>";
        });

        \Illuminate\Support\Facades\Blade::directive('idDayDate', function ($expression) {
            return "<?php echo format_id_day_date($expression); ?>";
        });

        \Illuminate\Support\Facades\Blade::directive('idMonthYear', function ($expression) {
            return "<?php echo format_id_month_year($expression); ?>";
        });

        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        \Illuminate\Database\Eloquent\Model::preventLazyLoading(!app()->isProduction());

        \Livewire\Livewire::component('cashflow', \App\Livewire\Cashflow\Index::class);
        \Livewire\Livewire::component('cashflow.index', \App\Livewire\Cashflow\Index::class);
    }
}
