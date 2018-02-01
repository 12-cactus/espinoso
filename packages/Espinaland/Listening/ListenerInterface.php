<?php

namespace Espinaland\Listening;

use Espinaland\Support\Objects\RequestMessageInterface;

/**
 * Interface ListenerInterface
 * @package Espinaland\Listening
 */
interface ListenerInterface
{
    public function lastMessage(): RequestMessageInterface;
}
