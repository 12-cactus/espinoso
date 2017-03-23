<?php
namespace App\Http\Controllers;

use Telegram\Bot\Laravel\Facades\Telegram;
use App\Espinoso\Espinoso ;

class TelegramController extends Controller
{
    public function handleUpdates()
    {
        try {
        $updates = Telegram::getWebhookUpdates();
        $updates = json_decode($updates);

        if($updates->message->text == 'send me nudes'){
            return Telegram::sendPhoto([
                'chat_id' => $updates->message->chat->id,
                'photo' => 'https://cdn.drawception.com/images/panels/2012/4-4/FErsE1a6t7-8.png',
                'caption' => 'Acá tenes tu nude hijo de puta!'
            ]);
        }

        Espinoso::handleTelegramUpdates($updates);
        } catch (\Exception $e)
        {
            Log::error($updates);
            Log::error($e);
        }
    }

    public function setWebhook()
    {
        return Telegram::setWebhook(['url' => 'https://espinoso.herokuapp.com/handle-update']);
    }

}
