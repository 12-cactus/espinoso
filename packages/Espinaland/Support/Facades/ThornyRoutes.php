<?php

namespace Espinaland\Support\Facades;

use Illuminate\Support\Facades\Facade;
use Espinaland\Support\ThornyRoutesHandler;
use Espinaland\Support\Objects\RequestMessageInterface;

/**
 * Class ThornyRoutes
 * @package Espinaland\Support\Facades
 *
 * @see ThornyRoutesHandler
 * @method handle(string $route, RequestMessageInterface $message, string $sender = 'telegram'): Response
 */
class ThornyRoutes extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'thorns';
    }
}
