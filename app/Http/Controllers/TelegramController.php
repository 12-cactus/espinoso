<?php

namespace App\Http\Controllers;

use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramController extends Controller
{
    public function handleUpdates()
    {
        $updates = Telegram::getWebhookUpdates();
        $updates = json_decode($updates);

        $text = $updates->message->text;
        $needle = "macri";

        if (strpos($text, $needle) !== false) {
            $response = Telegram::sendMessage([
                'chat_id' => $updates->message->chat->id,
                'text' => 'Gato'
            ]);
        }

        $text = $updates->message->text;
        $needle = "facu";

        if (strpos($text, $needle) !== false) {
            $response = Telegram::sendMessage([
                'chat_id' => $updates->message->chat->id,
                'text' => 'Facu... ese tipo es medio puto'
            ]);
        }

    }

    public function setWebhook()
    {
        return Telegram::setWebhook(['url' => 'https://espinoso.herokuapp.com/handle-update']);
    }
}
