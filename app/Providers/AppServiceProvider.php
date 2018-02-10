<?php

namespace App\Providers;

use App\Espinoso;
use Illuminate\Support\ServiceProvider;
use Espinaland\Deliveries\TelegramDelivery;
use Espinaland\Interpreters\SimplifierCollection;

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
        $this->registerSimplifierCollection();

        // Delivery Services
        $this->app->bind(TelegramDelivery::class, function () {
            return new TelegramDelivery(resolve('telegram'));
        });
        $this->app->alias(TelegramDelivery::class, 'telegram-delivery');

        // Espinoso
        $this->app->bind(Espinoso::class, function () {
            return new Espinoso(collect(config('espinoso.handlers')));
        });
    }

    /**
     * Register the parser collection instance.
     *
     * @return void
     */
    protected function registerSimplifierCollection()
    {
        $this->app->singleton(SimplifierCollection::class, function () {
            return new SimplifierCollection(config('espinoso.interpreters'));
        });
    }
}
