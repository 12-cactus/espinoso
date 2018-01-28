<?php

namespace App\Providers;

use App\Espinoso;
use App\Espinaland\Ruling\Rules;
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
        $this->registerRules();

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
        $this->app->singleton('rules', function () {
            return new Rules;
        });

        require app_path('rules.php');
    }
}
