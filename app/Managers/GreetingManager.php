<?php

namespace App\Managers;

use App\Espinaland\Support\Objects\RequestMessageInterface;
use App\Espinaland\Support\Objects\ResponseMessage;

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

    public function __construct(RequestMessageInterface $message)
    {
        $this->message = $message;
    }

    public function sayHi(): ResponseMessage
    {
        return new ResponseMessage([
            'chat-id' => $this->message->getChatId(),
            'text' => 'How you doing?'
        ]);
    }
}