<?php namespace App\Espinoso\Handlers;

use Telegram\Bot\Objects\Message;

class BardoDelEspinosoHandler extends EspinosoCommandHandler
{
    /**
     * @var string
     */
    protected $allow_ignore_prefix = true;
    /**
     * @var string
     */
    protected $pattern = "send me nudes$";

    public function shouldHandle(Message $message): bool
    {
        return $this->matchCommand($this->pattern, $message);
    }

    public function handle(Message $message)
    {
        return $this->telegram->sendPhoto([
            'chat_id' => $message->getChat()->getId(),
            'photo'   => 'https://cdn.drawception.com/images/panels/2012/4-4/FErsE1a6t7-8.png',
            'caption' => 'Acá tenés tu nude, hijo de puta!'
        ]);
    }
}
