<?php

namespace App\Objects\Telegram;

use App\Objects\ChatInterface;
use App\Objects\InputMessageInterface;
use Telegram\Bot\Objects\Message;

/**
 * Class TelegramInputMessage
 * @package App\Objects\Telegram
 */
class TelegramInputMessage implements InputMessageInterface
{
    /**
     * @var Message
     */
    protected $message;

    /**
     * TelegramInputMessage constructor.
     * @param Message $message
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function getChatId(): int
    {
        return $this->message->getChat()->getId();
    }
}
