<?php namespace App\Facades;

use Gmopx\LaravelOWM\LaravelOWM;
use Illuminate\Support\Facades\Facade;

/**
 * Class WeatherSearch
 * @see LaravelOWM
 */
class WeatherSearch extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'WeatherSearch';
    }
}
