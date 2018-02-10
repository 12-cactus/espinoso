<?php

namespace App\Providers;

use App\Espinoso;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Espinoso
        $this->app->bind(Espinoso::class, function () {
            return new Espinoso(collect(config('espinoso.handlers')));
        });
    }
}
