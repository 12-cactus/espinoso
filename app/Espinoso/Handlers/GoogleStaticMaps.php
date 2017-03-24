<?php
namespace App\Espinoso\Handlers ; 

use Telegram\Bot\Laravel\Facades\Telegram;

class GoogleStaticMaps extends EspinosoHandler
{
    const KEYWORD = 'gsm'; 

    public function shouldHandle($updates, $context=null) 
    {
        return  $this->isTextMessage($updates) && preg_match($this->regex(), $updates->message->text);
    }

    public function handle($updates, $context=null)
    {
        $location = $this->extractLocation($updates->message->text);
        $image = $this->getMapUrl($location);

        return Telegram::sendPhoto([
            'chat_id' => $updates->message->chat->id,
            'photo' => $image
        ]);
    }

    private function extractLocation($message) 
    {
        preg_match($this->regex(), $message, $matches);
        return $matches[1]; 
    }

    private function getMapUrl($location, $maptype='roadmap', $zoom=13, $size="600x500", $markerColor='blue')
    {
        $location = urlencode($location);
        return  "https://maps.googleapis.com/maps/api/staticmap?center=$location&zoom=$zoom&size=$size&maptype=$maptype&&markers=color:$markerColor%7Clabel:S%7C$location";
    }

    private function regex()
    {
        return "/^" . self::KEYWORD . "[ ]*(.*)$/i";
    }
}