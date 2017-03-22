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
        dump($updates);

        $handlers = EspinosoHandlers::getRegisteredHandlers();

        foreach ($handlers as $key => $handler)
            if ($handler->shouldHandle($updates))
                $handler->handle($updates);
    }

    public function setWebhook()
    {
        return Telegram::setWebhook(['url' => 'https://espinoso.herokuapp.com/handle-update']);
    }
}
