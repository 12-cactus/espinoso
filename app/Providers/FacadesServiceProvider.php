<?php

namespace App\Providers;

use Imdb\Config;
use Imdb\TitleSearch;
use Vinkla\Instagram\Instagram;
use Gmopx\LaravelOWM\LaravelOWM;
use Goutte\Client as GoutteClient;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\ServiceProvider;
use Stichoza\GoogleTranslate\TranslateClient;

class FacadesServiceProvider extends ServiceProvider
{
    protected $defer = true;

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        collect($this->facadesProvides())->each(function ($item, $key) {
            $this->app->bind($key, function () use ($item) {
                 return is_callable($item) ? $item() : $item;
            });
        });
    }

    public function provides()
    {
        return array_keys($this->facadesProvides());
    }

    protected function facadesProvides()
    {
        return [
            'GoutteClient' => new GoutteClient,
            'GuzzleClient' => new GuzzleClient,
            'InstagramSearch' => new Instagram(config('instagram.api_key')),
            'WeatherSearch' => new LaravelOWM,
            'IMDbSearch' => function () {
                $config = new Config;
                $config->language = 'es-AR,es,en';
                return new TitleSearch($config);
            },
            'Translator' => function () {
                $translator = new TranslateClient(null, 'es');
                $translator->setUrlBase(config('espinoso.url.traductor'));
                return $translator;
            },
        ];
    }
}
