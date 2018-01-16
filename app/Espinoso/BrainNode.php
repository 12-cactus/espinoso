<?php namespace App\Espinoso;

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

    public function pickReply(Message $message)
    {
        return is_array($this->reply)
            ? $this->pickFromBag($message)
            : $this->reply;
    }

    public function addIgnored(Collection $ignored)
    {
        $this->ignored->merge($ignored);
    }

    protected function shouldResponseTo(TelegramUser $from)
    {
        return true;
    }

    protected function pickFromBag(Message $message)
    {
        $number = mt_rand(0, count($this->reply) - 1);

        $reply = $this->reply[$number];

        if (str_contains($reply, ':name:')) {
            $reply = str_replace(':name:', $message->getFrom()->getFirstName(), $reply);
        }

        return $reply;
    }
}
