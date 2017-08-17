<?php namespace App\Espinoso\Handlers;

use App\Espinoso\Espinoso;
use App\Espinoso\BrainNode;
use Telegram\Bot\Objects\Message;
use Telegram\Bot\Api as ApiTelegram;

class BrainHandler extends EspinosoHandler
{
    protected $allNodes;
    protected $matchedNodes;

    protected $signature   = "macri, facu, ine, alan, asado, ...";
    protected $description = "Macri Gato, Facu Puto";

    public function __construct(Espinoso $espinoso, ApiTelegram $telegram)
    {
        parent::__construct($espinoso, $telegram);

        $this->matchedNodes = collect([]);
        $this->allNodes = collect(config('brain.patterns'))->map(function ($data, $regex) {
            return new BrainNode($regex, $data);
        });
    }

    public function shouldHandle(Message $message): bool
    {
        $this->matchedNodes = $this->allNodes->filter(function ($node) use ($message) {
            $node->addIgnored($this->globalIgnored());
            return $node->matchMessage($message);
        });

        return $this->matchedNodes->isNotEmpty();
    }

    public function handle(Message $message)
    {
        $this->matchedNodes->each(function (BrainNode $node) use ($message) {
            $this->telegram->sendMessage([
                'chat_id' => $message->getChat()->getId(),
                'text'    => $node->pickReply($message),
                'parse_mode' => 'Markdown'
            ]);
        });
    }

    /*
     * Internals
     */

    protected function globalIgnored()
    {
        return collect(config('brain.ignore_to'));
    }
    
}
