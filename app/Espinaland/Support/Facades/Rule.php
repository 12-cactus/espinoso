<?php

namespace App\Espinaland\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static Rule match(string $command, string $action)
 *
 * @see \App\Espinaland\Ruling\Rules
 */
class Rule extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'rules';
    }
}
