<?php

namespace App\Http\Managers;

use Telegram\Bot\Objects\Message;
use App\DeliveryServices\TelegramDelivery;
use Symfony\Component\HttpFoundation\Request;
use App\Objects\Telegram\TelegramRequestMessage;

/**
 * Class GreetingManager
 * @package App\Managers
 */
class GreetingManager
{
    public function hi(TelegramDelivery $delivery, Request $request)
    {
        $message = new Message($request->input('orig_message'));
        $message = new TelegramRequestMessage($message);

        $delivery->sendMessage([
            'chat_id' => $message->getChatId(),
            'text'    => 'aiiiuuuda',
        ]);
    }
}
