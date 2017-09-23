<?php namespace App\Espinoso\Handlers;

use Telegram\Bot\Objects\Message;

/**
 * Class BardoDelEspinosoHandler
 * @package App\Espinoso\Handlers
 */
class BardoDelEspinosoHandler extends EspinosoCommandHandler
{
    /**
     * @var string
     */
    protected $ignorePrefix = true;
    /**
     * @var string
     */
    protected $pattern = "send me nudes$";
    /**
     * @var string
     */
    protected $signature   = "[espi] send me nudes";
    /**
     * @var string
     */
    protected $description = "no sé, fijate";

    /**
     * @param Message $message
     */
    public function handle(Message $message): void
    {
        $this->espinoso->replyImage(
            'https://cdn.drawception.com/images/panels/2012/4-4/FErsE1a6t7-8.png',
            'Acá tenés tu nude, hijo de puta!'
        );
    }
}
