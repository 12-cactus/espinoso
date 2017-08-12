<?php namespace App\Espinoso\Handlers;

use App\Facades\InstagramSearch;
use Telegram\Bot\Objects\Message;
use Vinkla\Instagram\InstagramException;

class InstagramHandler extends EspinosoCommandHandler
{
    protected $allow_ignore_prefix = true;
    protected $pattern = "(\b(ig)\b)(\s+)(?'username'\b(\S+)\b)(?'param'\s*(last|pos:\d+))?$";

    public function handle(Message $message)
    {
        try {
            $username = $this->getUsername($message->getText());

            $this->telegram->sendPhoto([
                'chat_id' => $message->getChat()->getId(),
                'photo'   => $this->getImage($username, $this->getParam()),
                'caption' => "Ver https://www.instagram.com/{$username}"
            ]);
        } catch (InstagramException $e) {
            $this->replyNotFound($message);
        }
    }

    protected function getUsername()
    {
        return trim($this->matches['username']);
    }

    protected function getParam()
    {
        if (empty($this->matches['param'])) {
            return 'random';
        }

        $param = trim($this->matches['param']);

        if ($param == 'last') {
            return 0;
        }

        if (starts_with($param, 'pos:')) {
            return intval(explode(':', $param)[1]);
        }

        return $param;

    }

    protected function getImage($username, $param)
    {
        $response = InstagramSearch::get($username);

        $i = $param === 'random' ? rand(0, count($response) - 1) : intval($param);

        return $response[$i]['images']['standard_resolution']['url'];
    }
}
