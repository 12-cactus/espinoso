<?php

namespace App\Facades;


use Illuminate\Support\Facades\Facade;

class GoogleSearch extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'GoogleSearch';
    }
}
