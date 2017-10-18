<?php namespace App\Facades;

use Stichoza\GoogleTranslate\TranslateClient;
use Illuminate\Support\Facades\Facade;


class TranslateGClient extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'TranslateClient';
    }
}