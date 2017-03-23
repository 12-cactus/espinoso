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
        foreach ($this->mappings() as $pattern => $response)
        {
            $text = $this->processResponse($response, $pattern, $updates);

            if ( preg_match($pattern, $updates->message->text) ) 
            {
                Telegram::sendMessage([
                    'chat_id' => $updates->message->chat->id,
                    'text' => $text
                ]);
            }
        }
    }

    public function processResponse($response, $pattern, $updates)
    {
        if (is_array($response)) 
            return $this->choose($response);
        else if (is_callable($response))
            return $response($pattern, $updates);
        else 
            return $response; 
    }

    private function choose($responses) 
    {
        $key = array_rand($responses);
        return $responses[$key];
    }

    private function mappings()
    {
        return config('espinoso_data.ResponseByMatch.mappings');
    }
}