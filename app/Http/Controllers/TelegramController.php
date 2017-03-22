<?php
namespace App\Http\Controllers;

use Telegram\Bot\Laravel\Facades\Telegram;
use App\Telegram\Espinoso ;

class TelegramController extends Controller
{
    public function handleUpdates()
    {
        $updates = Telegram::getWebhookUpdates();
        $updates = json_decode($updates);

        Espinoso::handleTelegramUpdates($updates);
    }

    public function setWebhook()
    {
        return Telegram::setWebhook(['url' => 'https://espinoso.herokuapp.com/handle-update']);
    }
}
