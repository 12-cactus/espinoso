<?php

namespace App\Managers;

use App\Objects\OutputMessage;
use App\Objects\InputMessageInterface;

/**
 * Class GreetingManager
 * @package App\Managers
 */
class GreetingManager
{
    /**
     * @var InputMessageInterface
     */
    protected $message;

    public function __construct(InputMessageInterface $message)
    {
        $this->message = $message;
    }

    public function sayHi(): OutputMessage
    {
        return new OutputMessage([
            'chat-id' => $this->message->getChatId(),
            'text' => 'How you doing?'
        ]);
    }
}