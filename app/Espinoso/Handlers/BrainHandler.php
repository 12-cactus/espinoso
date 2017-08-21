<?php namespace App\Espinoso\Handlers;

use App\Espinoso\Espinoso;
use App\Espinoso\BrainNode;
use Telegram\Bot\Objects\Message;

/**
 * Class BrainHandler
 * @package App\Espinoso\Handlers
 */
class BrainHandler extends EspinosoHandler
{
    /**
     * @var static
     */
    protected $allNodes;
    /**
     * @var \Illuminate\Support\Collection
     */
    protected $matchedNodes;
    /**
     * @var string
     */
    protected $signature   = "macri, facu, ine, alan, asado, ...";
    /**
     * @var string
     */
    protected $description = "Macri Gato, Facu Puto";

    /**
     * BrainHandler constructor.
     * @param Espinoso $espinoso
     */
    public function __construct(Espinoso $espinoso)
    {
        parent::__construct($espinoso);

        $this->matchedNodes = collect([]);
        $this->allNodes = collect(config('brain.patterns'))->map(function ($data, $regex) {
            return new BrainNode($regex, $data);
        });
    }

    /**
     * @param Message $message
     * @return bool
     */
    public function shouldHandle(Message $message): bool
    {
        $this->matchedNodes = $this->allNodes->filter(function ($node) use ($message) {
            $node->addIgnored($this->globalIgnored());
            return $node->matchMessage($message);
        });

        return $this->matchedNodes->isNotEmpty();
    }

    /**
     * @param Message $message
     */
    public function handle(Message $message): void
    {
        $this->matchedNodes->each(function (BrainNode $node) use ($message) {
            $this->espinoso->reply($node->pickReply($message));
        });
    }

    /*
     * Internals
     */

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function globalIgnored()
    {
        return collect(config('brain.ignore_to'));
    }
    
}
