<?php
namespace App\Http\Controllers;

use Telegram\Bot\Laravel\Facades\Telegram;
use App\Telegram\Espinoso ;

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
            \Telegram\FuckHeroku::log($e);
        }
    }

    public function setWebhook()
    {
        return Telegram::setWebhook(['url' => 'https://espinoso.herokuapp.com/handle-update']);
    }

    public function freakingErrors()
    {
        return \Telegram\FuckHeroku::get_log($loggable);
    }
}
