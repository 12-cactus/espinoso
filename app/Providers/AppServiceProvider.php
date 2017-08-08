<?php

namespace App\Providers;

use App\Espinoso\Espinoso;
use Goutte\Client as GoutteClient;
use GuzzleHttp\Client as GuzzleClient;
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
        // Facades
        $this->app->bind('GoutteClient', function () { return new GoutteClient; });
        $this->app->bind('GuzzleClient', function () { return new GuzzleClient; });

        // Handlers
//        $handlers = collect(config('espinoso.handlers'));
//        $handlers->each(function ($handler) {
//            $this->app->bind($handler, function () use ($handler) {
//                return new $handler;
//            });
//        });

        // Espinoso
        $this->app->bind('Espinoso', function () { return new Espinoso; });
    }
}
