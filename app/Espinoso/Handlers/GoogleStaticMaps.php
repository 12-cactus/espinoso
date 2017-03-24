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
        $parameters = $this->extractParameters($updates->message->text);
        $image = $this->getMapUrl($location, $parameters);

        return Telegram::sendPhoto([
            'chat_id' => $updates->message->chat->id,
            'photo' => $image
        ]);
    }

    private function extractLocation($message) 
    {
        preg_match($this->regex(), $message, $matches);
        return $matches[2]; 
    }

    private function extractParameters($message)
    {
        $regex = '/[ ]*\-([a-z]):([^ \-])*/i';
        $matches = [] ; 
        $params = [] ; 
        preg_match_all($regex, $message, $matches, PREG_SET_ORDER);
        foreach($matches as [$disposable, $key, $value])
        {
            if ($this->isValidParamKey($key))
                $params[$this->getParamName($key)] = $value; 
        }
        return $params ; 
    }

    private function getMapUrl($location, $params)
    {
        extract(array_merge($this->defaults(), $params));

        $location = urlencode($location);
        return  "https://maps.googleapis.com/maps/api/staticmap?center=$location&zoom=$zoom&size=$size&maptype=$maptype&markers=color:$color%7Clabel:S%7C$location";
    }

    private function regex()
    {
        return "/^" . self::KEYWORD . "([ ]*\-[a-z]:[^ ])*[ ]*(.*)$/i";
    }

    private function defaults()
    {
        return ['maptype' => 'roadmap', 'zoom'=> 13, 'size'=>"600x500", 'color'=>'blue'];
    }

    private function paramsMapping()
    {
        return [ 'z' => 'zoom', 't' => 'maptype', 's' => 'size', 'c' => 'color', ];
    }

    private function isValidParamKey($key)
    {
        return array_key_exists($key, $this->paramsMapping());   
    }

    private function getParamName($key)
    {
        return $this->paramsMapping()[$key];
    }
}