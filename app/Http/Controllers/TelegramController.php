<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\Espinoso\Espinoso ;

class TelegramController extends Controller
{
    public function handleUpdates()
    {
        try {
            $updates = Telegram::getWebhookUpdates();
            $updates = json_decode($updates);

            Espinoso::handleTelegramUpdates($updates);

        } catch (\Exception $e)
        {
            Log::error(json_encode($updates));
            Log::error($e);
        }
    }

    public function setWebhook()
    {
        return Telegram::setWebhook(['url' => 'https://espinoso.herokuapp.com/handle-update']);
    }

    public function githubWebhook()
    {
        Telegram::sendMessage([
            'chat_id' => -205010293,
            'text' => 'Alguien esta comiteando boludeces.. autodestrucción en 3, 2, 1...'
        ]);
    }
}