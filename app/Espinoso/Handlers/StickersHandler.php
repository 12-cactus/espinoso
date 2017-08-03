<?php namespace App\Espinoso\Handlers;

use Telegram\Bot\Laravel\Facades\Telegram;

class StickersHandler extends EspinosoCommandHandler
{
    /**
     * FIXME load from some config
     * @var string
     */
    protected $patterns = [
        [
            'user' => 'Facundo',
            'pattern' => ".*\bmaybe\b.*",
            'sticker' => 'CAADAgADiwUAAvoLtgh812FBxEdUAgI' // LazyPanda
        ]
    ];
    protected $match = null;

    protected $allow_ignore_prefix = true;

    public function shouldHandle($updates, $context = null)
    {
        $this->match = collect($this->patterns)->filter(function ($pattern) use ($updates) {
            // FIXME that shit
            return isset($updates->message)
                && isset($updates->message->from)
                && isset($updates->message->from->first_name)
                && $updates->message->from->first_name === $pattern['user']
                && $this->matchCommand($pattern['pattern'], $updates);
        });

        return $this->match->isNotEmpty();
    }

    public function handle($updates, $context = null)
    {
        Telegram::sendSticker([
            'chat_id' => $updates->message->chat->id,
            'sticker' => $this->match->first()['sticker'],
        ]);
    }
}
