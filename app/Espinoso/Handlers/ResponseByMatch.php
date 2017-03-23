<?php
namespace App\Espinoso\Handlers ; 

use Telegram\Bot\Laravel\Facades\Telegram;

class ResponseByMatch extends EspinosoHandler
{
    public function shouldHandle($updates, $context=null) 
    {
        if ( ! $this->isTextMessage($updates) ) return false ; 

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
                    'text' => $response
                ]);
            }
        }
    }

    private function mappings()
    {
        return config('espinoso_data.ResponseByMatch.mappings');
    }
}