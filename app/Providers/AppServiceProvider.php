<?php

namespace App\Providers;

use Filament\Filament;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }
        config([
            'filament.widgets.default.account' => false,
            'filament.widgets.default.info' => false,
        ]);

        Filament::registerStyle('water-service-admin', asset('css/admin-crm.css'));
    }
}
