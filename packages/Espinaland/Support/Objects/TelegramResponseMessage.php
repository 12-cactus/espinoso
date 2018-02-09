<?php

namespace Espinaland\Support\Objects;

use Telegram\Bot\Objects\Message;

/**
 * Class ResponseMessage
 * @package App\Objects\Telegram
 */
class TelegramResponseMessage extends ResponseMessage
{
    /**
     * @var Message
     */
    private $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function getCode(): int
    {
        return $this->message->getStatus() ? 200 : 512;
    }

    public function getMessage(): string
    {
        return $this->message->getStatus() ? 'OK' : 'ERROR';
    }
}
