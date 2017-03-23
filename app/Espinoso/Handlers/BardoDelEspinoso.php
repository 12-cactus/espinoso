<?php
namespace App\Espinoso\Handlers ; 

use Telegram\Bot\Laravel\Facades\Telegram;

class BardoDelEspinoso implements EspinosoHandler
{
    public function shouldHandle($updates, $context=null) 
    {
        // me aseguro que el request que vino trae los datos que quiero... 
        if ( ! (isset($updates->message) && isset($updates->message->text)) )
            return false ; 

        return ( preg_match('/^send me nudes$/i', $updates->message->text) )
    }

    public function handle($updates, $context=null)
    {
        return Telegram::sendPhoto([
            'chat_id' => $updates->message->chat->id,
            'photo' => 'https://cdn.drawception.com/images/panels/2012/4-4/FErsE1a6t7-8.png',
            'caption' => 'Acá tenes tu nude hijo de puta!'
        ]);
    }
}