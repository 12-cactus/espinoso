<?php

namespace App\Espinoso;
use Illuminate\Support\Collection;
use Telegram\Bot\Objects\Message;
use Telegram\Bot\Objects\User as TelegramUser;

/**
 * Class BrainNode
 * @package App\Espinoso
 */
class BrainNode
{
    protected $regex;
    protected $reply;
    protected $match;
    protected $matches;
    protected $ignored;

    public function __construct(string $regex, array $data = [])
    {
        $this->regex = $regex;
        $this->reply = $data['reply'] ?? '';
        $this->ignored = collect($data['ignored'] ?? []);
    }

    public function matchMessage(Message $message)
    {
        $this->match = preg_match($this->regex, $message->getText(), $this->matches) === 1;

        return !empty($this->reply)
            && $this->shouldResponseTo($message->getFrom())
            && $this->match;
    }

    public function pickReply()
    {
        return is_array($this->reply)
            ? $this->pickFromBag()
            : $this->reply;
    }

    public function addIgnored(Collection $ignored) {
        $this->ignored->merge($ignored);
    }

    protected function shouldResponseTo(TelegramUser $from)
    {
        // TODO
        return true;
    }

    protected function pickFromBag()
    {
        // FIXME: make a better behavior than simple random
        $number = rand(0, count($this->reply) - 1);

        return $this->reply[$number];
    }

}