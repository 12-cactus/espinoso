<?php

namespace App\Providers;

use App\Espinoso\DeliveryServices\TelegramDelivery;
use Imdb\Config;
use Imdb\TitleSearch;
use App\Espinoso\Espinoso;
use Stichoza\GoogleTranslate\TranslateClient;
use Telegram\Bot\Api;
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
        $this->bindHandlersFacades();

        // Delivery Services
        $this->app->bind(TelegramDelivery::class, function () {
            return new TelegramDelivery(resolve('telegram'));
        });

        // Espinoso
        $this->app->bind(Espinoso::class, function () {
            return new Espinoso(collect(config('espinoso.handlers')));
        });
    }

    protected function bindHandlersFacades(): void
    {
        $this->app->bind('GoutteClient', function () {
            return new GoutteClient;
        });

        $this->app->bind('GuzzleClient', function () {
            return new GuzzleClient;
        });

        $this->app->bind('InstagramSearch', function () {
            return new Instagram;
        });

        $this->app->bind('WeatherSearch', function () {
            return new LaravelOWM;
        });

        $this->app->bind('IMDbSearch', function () {
            $config = new Config;
            $config->language = 'es-AR,es,en';
            return new TitleSearch($config);
        });

        $this->app->bind('Translator', function () {
            $translator = new TranslateClient(null, 'es');
            $translator->setUrlBase(config('espinoso.url.traductor'));
            return $translator;
        });
    }
}
