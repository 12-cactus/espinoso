<?php

namespace App\Providers;

use App\Espinoso;
use Espinaland\Ruling\Rules;
use Illuminate\Support\ServiceProvider;
use App\DeliveryServices\TelegramDelivery;
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
