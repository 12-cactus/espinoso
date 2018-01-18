<?php

namespace App\Providers;

use App\Espinoso\Espinoso;
use Illuminate\Support\ServiceProvider;
use App\DeliveryServices\TelegramDelivery;

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
        // Delivery Services
        $this->app->bind(TelegramDelivery::class, function () {
            return new TelegramDelivery(resolve('telegram'));
        });

        // Espinoso
        $this->app->bind(Espinoso::class, function () {
            return new Espinoso(collect(config('espinoso.handlers')));
        });
    }
}
