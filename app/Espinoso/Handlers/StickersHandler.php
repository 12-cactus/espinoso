<?php namespace App\Espinoso\Handlers;

use Telegram\Bot\Objects\Message;

class StickersHandler extends EspinosoCommandHandler
{
    /**
     * @var bool
     */
    protected $ignorePrefix = true;
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

    protected $signature   = "[espi] maybe";
    protected $description = "solo funciona para facu... los demás a comerla";


    /**
     * @var null
     */
    protected $match = null;

    public function shouldHandle(Message $message): bool
    {
        $this->message = $message;

        $this->match = collect($this->patterns)->filter(function ($pattern) {
            return $this->message->getFrom()->getFirstName() === $pattern['user']
                && $this->matchCommand($pattern['pattern'], $this->message);
        });

        return $this->match->isNotEmpty();
    }

    public function handle(): void
    {
        $this->espinoso->replySticker($this->match->first()['sticker']);
    }
}
