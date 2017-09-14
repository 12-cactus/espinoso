<?php namespace App\Espinoso\Handlers;

use Telegram\Bot\Objects\Message;

class GifsHandler extends EspinosoCommandHandler
{
    /**
     * @var bool
     */
    protected $allow_ignore_prefix = true;
    /**
     * @var string
     */

    protected $patterns = [
        [
            'pattern' => "\b(dracarys)\b",
            'video'   => 'dracarys.mp4'
        ],
        [
            'pattern' => "\b(espinarys)\b",
            'video'   => 'espinarys.gif'
        ],
        [
            'pattern' => "\b(cold walk)\b",
            'video'   => 'cold-walk.gif'
        ],
        [
            'pattern' => "\b(pochoclos)\b",
            'video'   => 'pochoclos.mp4'
        ]
    ];

    protected $signature   = "[espi] dracarys";
    protected $description = "Valar Morghulis";


    /**
     * @var null
     */
    protected $match = null;

    public function shouldHandle(Message $message): bool
    {
        $this->match = collect($this->patterns)->filter(function ($pattern) use ($message) {
            return $this->matchCommand($pattern['pattern'], $message);
        });

        return $this->match->isNotEmpty();
    }

    public function handle(Message $message): void
    {
        $this->espinoso->replyGif(public_path('gifs/'.$this->match->first()['video']));
    }
}
