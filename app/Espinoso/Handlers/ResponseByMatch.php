<?php namespace App\Espinoso\Handlers;

use App\Espinoso\Helpers\Msg;
use Telegram\Bot\Objects\Message;

class ResponseByMatch extends EspinosoHandler
{
    public function shouldHandle(Message $message): bool
    {
        foreach ($this->mappings() as $needle => $response)
            if ( preg_match($needle, $message->getText()) )
                return true;
        return false;
    }

    public function handle(Message $message)
    {
        if ($this->ignoringSender($message->getFrom())) {
            $fromName = $message->getFrom()->getFirstName();
            $msg = Msg::md("Con vos no hablo porque no viniste al asado $fromName")->build($message);
            $this->telegram->sendMessage($msg);
            return;
        }

        foreach ($this->mappings() as $pattern => $response) {
            if ( preg_match($pattern, $message->getText()) ) {
                $msg = $this->buildMessage($response, $pattern, $message);
                $this->telegram->sendMessage($msg);
            }
        }
    }

    private function buildMessage($response, $pattern, Message $message)
    {
        if ($response instanceof Msg)
            return $response->build($message, $pattern);
        else 
            return Msg::plain($response)->build($message, $pattern);
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
