<?php namespace App\Facades;

use Imdb\TitleSearch;
use Illuminate\Support\Facades\Facade;

/**
 * Class IMDbSearch
 * @see TitleSearch
 */
class IMDbSearch extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'IMDbSearch';
    }
}
