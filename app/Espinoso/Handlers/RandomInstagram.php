<?php
namespace App\Espinoso\Handlers ;

use App\Espinoso\Helpers\Msg;
use Telegram\Bot\Objects\Message;
use Vinkla\Instagram\Instagram;
use Vinkla\Instagram\InstagramException;

class RandomInstagram extends EspinosoHandler
{
    const KEYWORD = 'ig'; 

    public function shouldHandle(Message $message): bool
    {
        return preg_match($this->regex(), $message->getText());
    }

    public function handle(Message $message)
    {
        try {
            $user = $this->extract_user($message->getText());
            $image = $this->get_random_image($user);
            return $this->telegram->sendPhoto([
                'chat_id' => $message->getChat()->getId(),
                'photo' => $image
            ]);
        } catch (InstagramException $e) {
            return $this->telegram->sendMessage( Msg::plain("no papu, le erraste de instagram")->build($message) );
        }
    }

    private function extract_user($message) 
    {
        preg_match($this->regex(), $message, $matches);
        return $matches[1]; 
    }

    private function get_random_image($user)
    {
        $instagram = new Instagram();
        $response = $instagram->get($user);
        if (empty($response))
            throw new InstagramException("no media found");
        $i = array_rand($response);
        return $response[$i]['images']['low_resolution']['url'];
    }

    private function regex()
    {
        return "/^" . self::KEYWORD . "[ ]*([^ ]*)$/i";
    }
}
