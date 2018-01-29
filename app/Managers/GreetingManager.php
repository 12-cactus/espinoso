<?php

namespace App\Managers;

use Espinaland\Support\Objects\RequestMessageInterface;
use Espinaland\Support\Objects\ResponseMessage;

/**
 * Class GreetingManager
 * @package App\Managers
 */
class GreetingManager
{
    /**
     * @var RequestMessageInterface
     */
    protected $message;

    public function __construct(RequestMessageInterface $message)
    {
        $this->message = $message;
    }

    public function sayHi(): ResponseMessage
    {
        logger('sayHi inside');

        return new ResponseMessage([
            'chat-id' => $this->message->getChatId(),
            'text' => 'How you doing?'
        ]);
    }
}