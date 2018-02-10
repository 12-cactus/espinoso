<?php

namespace Espinaland\Providers;

use Espinaland\Deliveries\TelegramDelivery;
use Espinaland\Interpreters\SimplifierCollection;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Espinaland\Support\ThornyRoutesHandler;

/**
 * Class EspinalandServiceProvider
 * @package Espinaland\Providers
 */
class EspinalandServiceProvider extends ServiceProvider
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

        $this->app->bind(TelegramDelivery::class, function () {
            return new TelegramDelivery(resolve('telegram'));
        });
        $this->app->alias(TelegramDelivery::class, 'telegram-delivery');

        $this->app->singleton(SimplifierCollection::class, function () {
            return new SimplifierCollection(config('espinoso.interpreters'));
        });

        $this->app->singleton('thorns', ThornyRoutesHandler::class);

        $this->mapRoutes();
    }

    /**
     * Define the "espi" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapRoutes()
    {
        Route::prefix('espi')
            ->middleware('web')
            ->middleware('espi')
            ->namespace('App\Http\Managers')
            ->group(base_path('routes/espi.php'));
    }
}
