<?php

namespace Espinaland\Support\Facades;

use Illuminate\Support\Facades\Facade;
use Espinaland\Support\Objects\ResponseMessage;

/**
 * Class ReplyResponses
 * @package Espinaland\Support\Facades
 *
 * @see \Espinaland\Responses\ReplyResponses
 * @method ResponseMessage text(string $text)
 */
class ReplyResponses extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'reply';
    }
}
