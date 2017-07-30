<?php namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class GuzzleClient
 * @see \GuzzleHttp\Client
 */
class GuzzleClient extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'GuzzleClient';
    }
}
