<?php

namespace Espinaland\Responses;

use Illuminate\Http\Response;
use Espinaland\Support\Objects\ResponseMessage;
use Illuminate\Support\Facades\Response as FacadeResponse;

/**
 * Class ReplyResponses
 * @package Espinaland\Responses
 */
class ReplyResponses extends ThornyResponses
{
    protected $text;

    public function text(string $text): Response
    {
        $this->text = $text;
        return FacadeResponse::make($this);
    }

    public function apply(): ResponseMessage
    {
        return $this->delivery->sendMessage([
            'chat_id' => $this->delivery->lastMessage()->getChatId(),
            'text'    => $this->text,
        ]);
    }
}
