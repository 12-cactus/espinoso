<?php namespace App\Espinoso\Handlers;

use Telegram\Bot\Objects\Message;

class StickersHandler extends EspinosoCommandHandler
{
    /**
     * @var bool
     */
    protected $allow_ignore_prefix = true;
    /**
     * FIXME load from some config
     * @var string
     */
    protected $patterns = [
        [
            'user' => 'Facundo',
            'pattern' => ".*\bmaybe\b.*",
            'sticker' => 'CAADAgADiwUAAvoLtgh812FBxEdUAgI' // LazyPanda FIXME put on agnostic class
        ]
    ];
    /**
     * @var null
     */
    protected $match = null;

    public function shouldHandle(Message $message): bool
    {
        $this->match = collect($this->patterns)->filter(function ($pattern) use ($message) {
            return $message->getFrom()->getFirstName() === $pattern['user']
                && $this->matchCommand($pattern['pattern'], $message);
        });

        return $this->match->isNotEmpty();
    }

    public function handle(Message $message)
    {
        $this->telegram->sendSticker([
            'chat_id' => $message->getChat()->getId(),
            'sticker' => $this->match->first()['sticker'],
        ]);
    }
}
