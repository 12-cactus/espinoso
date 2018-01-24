<?php

namespace App\Objects;

use Illuminate\Support\Collection;

/**
 * Class OutputMessage
 * @package App\Objects\Telegram
 */
class OutputMessage
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
