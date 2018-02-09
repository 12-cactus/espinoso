<?php

namespace App\Http\Managers;

use Espinaland\Support\Facades\ReplyResponses;

/**
 * Class GreetingManager
 * @package App\Managers
 */
class GreetingManager
{
    public function cool()
    {
        return ReplyResponses::text('cool, men!');
    }
}
