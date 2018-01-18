<?php

namespace App\Handlers;

use App\Espinoso;
use App\BrainNode;
use Telegram\Bot\Objects\Message;

/**
 * Class BrainHandler
 * @package App\Handlers
 */
class BrainHandler extends BaseHandler
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
     * BrainHandler constructor.
     * @param Espinoso $espinoso
     */
    public function __construct(Espinoso $espinoso)
    {
        parent::__construct($espinoso);

        $this->matchedNodes = collect([]);
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
