<?php

namespace App\Providers;

use App\Models\Barcode;
use App\Observers\BarcodeObserver;
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
        Barcode::observe(BarcodeObserver::class);
    }
}
