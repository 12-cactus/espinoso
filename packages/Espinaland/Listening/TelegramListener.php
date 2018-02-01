<?php

namespace Espinaland\Listening;

use App\DeliveryServices\TelegramDelivery;
use Espinaland\Support\Objects\RequestMessageInterface;

/**
 * Class TelegramListener
 * @package Espinaland\Listening
 */
class TelegramListener implements ListenerInterface
{
    public function lastMessage(): RequestMessageInterface
    {
        $delivery = resolve(TelegramDelivery::class);

        return $delivery->getIncomingMessage();
    }
}
