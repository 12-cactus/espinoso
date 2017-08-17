<?php namespace App\Espinoso\Handlers;

use App\Espinoso\Espinoso;
use App\Espinoso\BrainNode;
use Telegram\Bot\Objects\Message;
use Telegram\Bot\Api as ApiTelegram;
use App\Espinoso\DeliveryServices\EspinosoDeliveryInterface;

class BrainHandler extends EspinosoHandler
{
    protected $allNodes;
    protected $matchedNodes;

    protected $signature   = "macri, facu, ine, alan, asado, ...";
    protected $description = "Macri Gato, Facu Puto";

    public function __construct(Espinoso $espinoso, EspinosoDeliveryInterface $delivery)
    {
        parent::__construct($espinoso, $delivery);

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

    public function handle(Message $message): void
    {
        $this->matchedNodes->each(function (BrainNode $node) use ($message) {
            $this->espinoso->reply($node->pickReply($message));
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
