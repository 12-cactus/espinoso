<?php

namespace App\Http\Controllers;

use Telegram\Bot\Laravel\Facades\Telegram;
use App\Telegram\EspinosoHandlers ;

class TelegramController extends Controller
{
    public function handleUpdates()
    {
        $updates = Telegram::getWebhookUpdates();
        $updates = json_decode($updates);

        $handlers = EspinosoHandlers::getRegisteredHandlers();

        $handlers->each( 
            function ($handler,$key) use ($updates) 
            {
                if ($handler->shouldHandle($updates))
                    $handler->handle($updates);
            });
    }

    public function setWebhook()
    {
        return Telegram::setWebhook(['url' => 'https://espinoso.herokuapp.com/handle-update']);
    }
}
