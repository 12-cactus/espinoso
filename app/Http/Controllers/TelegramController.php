<?php
namespace App\Http\Controllers;

use Telegram\Bot\Laravel\Facades\Telegram;
use Vinkla\Instagram\Instagram;
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

        if($updates->message->text == '9gag'){
            $instagram = new Instagram();
            $response = $instagram->get(env('INSTAGRAM_USER'));
            $image = $response[array_rand($response)]['images']['low_resolution']['url'];

            return Telegram::sendPhoto([
                'chat_id' => $updates->message->chat->id,
                'photo' => $image,
                'caption' => 'dat ass'
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
