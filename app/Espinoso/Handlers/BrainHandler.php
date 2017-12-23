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
        dump(trans('brain.patterns'));
        $this->allNodes = collect(trans('brain.patterns'))->map(function ($data, $regex) {
            return new BrainNode($regex, $data);
        });
    }

    /**
     * @param Message $message
     * @return bool
     */
    public function shouldHandle(Message $message): bool
    {
        $this->message = $message;

        $this->matchedNodes = $this->allNodes->filter(function ($node) {
            $node->addIgnored($this->globalIgnored());
            return $node->matchMessage($this->message);
        });

        return $this->matchedNodes->isNotEmpty();
    }

    /**
     *
     */
    public function handle(): void
    {
        $this->matchedNodes->each(function (BrainNode $node) {
            $this->espinoso->reply($node->pickReply($this->message));
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
        return collect(trans('brain.ignore_to'));
    }
}