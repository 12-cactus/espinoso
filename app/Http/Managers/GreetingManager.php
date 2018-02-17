<?php

namespace App\Http\Managers;

use Espinaland\Deliveries\DeliveryInterface;
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

    public function coolNamed(DeliveryInterface $delivery)
    {
        $name = $delivery->getMessage()->getSenderName();

        return ReplyResponses::text("cool, {$name}!");
    }
}
