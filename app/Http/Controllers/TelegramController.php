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
            trigger_error(var_export($e->getTraceAsString(), true), E_USER_ERROR);

        }
    }

    public function setWebhook()
    {
        return Telegram::setWebhook(['url' => 'https://espinoso.herokuapp.com/handle-update']);
    }
}
