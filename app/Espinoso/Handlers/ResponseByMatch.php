<?php
namespace App\Espinoso\Handlers ; 

use \App\Espinoso\Helpers\Msg;
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
        if ($this->ignoringSender($updates->message->from))
        {
            $fromName = $updates->message->from->first_name;
            $msg = Msg::md("Con vos no hablo porque no viniste al asado $fromName")->build('', $updates);
            Telegram::sendMessage($msg); 
            return ; 
        }

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
            return $response->build($updates, $pattern);
        else 
            return Msg::plain($response)->build($updates, $pattern);
    }
 
    private function mappings()
    {
        return config('espinoso_data.ResponseByMatch.mappings');
    }
    
    private function ignoredNames()
    {
        return config('espinoso_data.ResponseByMatch.ignore_names');
    }

    private function ignoringSender($sender)
    {
        foreach ($this->ignoredNames() as $name)
            if ( preg_match("/$name/i", $sender->first_name) )
                return true ; 
        return false ; 
    }
    
}


