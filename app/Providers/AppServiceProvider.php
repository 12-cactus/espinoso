<?php

namespace App\Providers;

use App\Espinoso;
use Espinaland\Ruling\Rules;
use Illuminate\Support\ServiceProvider;
use App\DeliveryServices\TelegramDelivery;
use Espinaland\Parsing\ParserCollection;

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
        $this->registerRules();

        $this->registerParserCollection();

        // Delivery Services
        $this->app->bind(TelegramDelivery::class, function () {
            return new TelegramDelivery(resolve('telegram'));
        });

        // Espinoso
        $this->app->bind(Espinoso::class, function () {
            return new Espinoso(collect(config('espinoso.handlers')));
        });
    }

    /**
     * Register the rules instance.
     *
     * @return void
     */
    protected function registerRules()
    {
        $this->app->singleton(Rules::class, function () {
            return new Rules;
        });

        $this->app->alias(Rules::class, 'rules');

        require app_path('rules.php');
    }

    /**
     * Register the parser collection instance.
     *
     * @return void
     */
    protected function registerParserCollection()
    {
        $this->app->singleton(ParserCollection::class, function () {
            return new ParserCollection(config('espinoso.parsers'));
        });
    }
}
