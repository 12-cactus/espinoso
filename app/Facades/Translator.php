<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;
use Stichoza\GoogleTranslate\TranslateClient;

/**
 * Class Translator
 * @package App\Facades
 * @see TranslateClient
 */
class Translator extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'Translator';
    }
}
