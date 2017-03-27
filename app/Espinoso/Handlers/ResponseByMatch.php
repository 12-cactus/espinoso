<?php
namespace App\Espinoso\Handlers ; 

use \App\Espinoso\Helpers\Msg;
use Telegram\Bot\Laravel\Facades\Telegram;

class ResponseByMatch extends EspinosoHandler
{
    public function shouldHandle($updates, $context=null) 
    {
        if ( ! $this->isTextMessage($updates) ) return false ; 
        
        foreach ($this->ignoredNames() as $name)
            if ( preg_match("/$name/i", $updates->message->from->first_name)
               || preg_match("/$name/i", $updates->message->from->username) )
                return false ; 

        foreach ($this->mappings() as $needle => $response)
            if ( preg_match($needle, $updates->message->text) )
                return true ; 
        return false ; 
    }

    public function handle($updates, $context=null)
    {
        foreach ($this->mappings() as $pattern => $response)
        {
            if ( preg_match($pattern, $updates->message->text) ) 
            {
                $msg = $this->buildMessage($response, $pattern, $updates);
                Telegram::sendMessage($msg);
            }
        }
    }

    private function buildMessage($response, $pattern, $updates)
    {
        if ($response instanceof Msg)
            return $response->build($pattern, $updates);
        else 
            return Msg::plain($response)->build($pattern, $updates);
    }
 
    private function mappings()
    {
        return config('espinoso_data.ResponseByMatch.mappings');
    }
    
    private function ignoredNames()
    {
        return config('espinoso_data.ResponseByMatch.ignore_names');
    }

}


