<?php

namespace App\Providers;

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
        $handlers = collect(config('espinoso.handlers'));
        $handlers->each(function ($handler) {
            $this->app->bind($handler, function () use ($handler) {
                return new $handler;
            });
        });
    }
}
