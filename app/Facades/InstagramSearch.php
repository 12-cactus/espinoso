<?php namespace App\Facades;

use Vinkla\Instagram\Instagram;
use Illuminate\Support\Facades\Facade;

/**
 * Class InstagramSearch
 * @see Instagram
 */
class InstagramSearch extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'InstagramSearch';
    }
}
