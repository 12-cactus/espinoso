<?php

namespace App\Providers;

use Imdb\Config;
use Imdb\TitleSearch;
use App\Espinoso\Espinoso;
use Vinkla\Instagram\Instagram;
use Gmopx\LaravelOWM\LaravelOWM;
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
        $this->app->bind('InstagramSearch', function () { return new Instagram; });
        $this->app->bind('WeatherSearch', function () { return new LaravelOWM; });
        $this->app->bind('IMDbSearch', function () {
            $config = new Config;
            $config->language = 'es-AR,es,en';
            return new TitleSearch($config);
        });

        // Espinoso
        $this->app->bind(Espinoso::class, function () {
            return new Espinoso(collect(config('espinoso.handlers')));
        });
    }
}
