<?php

namespace Espinaland\Providers;

use Illuminate\Support\ServiceProvider;
use Espinaland\Support\ThornyRoutesHandler;

/**
 * Class EspinalandServiceProvider
 * @package Espinaland\Providers
 */
class EspinalandServiceProvider extends ServiceProvider
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
        $this->app->singleton('thorns', ThornyRoutesHandler::class);
    }
}
