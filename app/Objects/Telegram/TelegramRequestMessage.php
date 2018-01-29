<?php

namespace App\Objects\Telegram;

use Telegram\Bot\Objects\Message;
use App\Espinaland\Support\Objects\RequestMessageInterface;

/**
 * Class TelegramRequestMessage
 * @package App\Objects\Telegram
 */
class TelegramRequestMessage implements RequestMessageInterface
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

    public function getTextMessage(): string
    {
        return $this->message->getText();
    }

    public function __toString(): string
    {
        return $this->message;
    }
}
