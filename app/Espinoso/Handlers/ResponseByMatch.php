<?php
namespace App\Espinoso\Handlers ; 

use Telegram\Bot\Laravel\Facades\Telegram;

class ResponseByMatch implements EspinosoHandler
{
    public function shouldHandle($updates, $context=null) 
    {
        // me aseguro que el request que vino trae los datos que quiero... 
        if ( ! (isset($updates->message) && isset($updates->message->text)) )
            return false ; 

        foreach ($this->mappings() as $needle => $response)
            if ( preg_match($needle, $updates->message->text) )
                return true ; 
        return false ; 
    }

    public function handle($updates, $context=null)
    {
        foreach ($this->mappings() as $needle => $response)
        {
            if ( preg_match($needle, $updates->message->text) ) 
            {
                $response = Telegram::sendMessage([
                    'chat_id' => $updates->message->chat->id,
                    'text' => $response . ' ' . $updates->message->chat->id
                ]);
            }
        }
    }

    private function mappings()
    {
        return config('espinoso_data.ResponseByMatch.mappings');
    }
}