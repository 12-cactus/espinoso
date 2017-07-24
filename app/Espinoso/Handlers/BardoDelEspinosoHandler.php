<?php namespace App\Espinoso\Handlers;

use Telegram\Bot\Laravel\Facades\Telegram;

class BardoDelEspinosoHandler extends EspinosoHandler
{
    public function shouldHandle($updates, $context = null)
    {
        return $this->isTextMessage($updates)
            && preg_match('/^send me nudes$/i', $updates->message->text) ;
    }

    public function handle($updates, $context = null)
    {
        return Telegram::sendPhoto([
            'chat_id' => $updates->message->chat->id,
            'photo'   => 'https://cdn.drawception.com/images/panels/2012/4-4/FErsE1a6t7-8.png',
            'caption' => 'Acá tenés tu nude, puto del orto!'
        ]);
    }
}