<?php
namespace App\Espinoso\Handlers ; 

use Telegram\Bot\Laravel\Facades\Telegram;
use Vinkla\Instagram\Instagram;
use Vinkla\Instagram\InstagramException;

class RandomInstagram extends EspinosoHandler
{
    const KEYWORD = 'ig'; 

    public function shouldHandle($updates, $context=null) 
    {
        return  $this->isTextMessage($updates) && preg_match($this->regex(), $updates->message->text);
    }

    public function handle($updates, $context=null)
    {
        try {
            $user = $this->extract_user($updates->message->text);
            $image = $this->get_random_image($user);
            return Telegram::sendPhoto([
                'chat_id' => $updates->message->chat->id,
                'photo' => $image
            ]);
        } catch (InstagramException $e) {
            return Telegram::sendMessage( Msg::plain("no papu, le erraste de instagram")->build($updates) );
        }
    }

    private function extract_user($message) 
    {
        // $user = env('INSTAGRAM_USER');
        preg_match($this->regex(), $message, $matches);
        return $matches[1]; 
    }

    private function get_random_image($user)
    {
        $instagram = new Instagram();
        $response = $instagram->get($user);
        $i = array_rand($response);
        if (is_null($i))
            throw new InstagramException("no media found");
        return $response[$i]['images']['low_resolution']['url'];
    }

    private function regex()
    {
        return "/^" . self::KEYWORD . "[ ]*([^ ]*)$/i";
    }
}
