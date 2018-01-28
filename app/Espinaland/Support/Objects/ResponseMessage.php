<?php

namespace App\Espinaland\Support\Objects;

use Illuminate\Support\Collection;

/**
 * Class ResponseMessage
 * @package App\Objects\Telegram
 */
class ResponseMessage
{
    /**
     * @var Collection
     */
    protected $data;

    public function __construct(array $data = [])
    {
        $this->data = collect($data);
    }

    public function getChatId(): int
    {
        return $this->data->get('chat-id');
    }

    public function getText(): string
    {
        return $this->data->get('text');
    }
}
