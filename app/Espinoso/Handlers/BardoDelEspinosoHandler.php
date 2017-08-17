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

    protected $signature   = "[espi] send me nudes";
    protected $description = "no sé, fijate";

    public function handle(Message $message): void
    {
        $this->telegram->sendPhoto([
            'chat_id' => $message->getChat()->getId(),
            'photo'   => 'https://cdn.drawception.com/images/panels/2012/4-4/FErsE1a6t7-8.png',
            'caption' => 'Acá tenés tu nude, hijo de puta!'
        ]);
    }
}
