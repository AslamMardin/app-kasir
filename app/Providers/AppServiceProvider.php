<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        try {
            $appName = \App\Models\Setting::where('key', 'app_name')->first()->value ?? 'Toko Campalagian';
            \Illuminate\Support\Facades\View::share('appName', $appName);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\View::share('appName', 'Toko Campalagian');
        }
    }
}
