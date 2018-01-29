<?php

namespace Espinaland\Support\Objects;

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
        $data = array_merge([
            'type_text' => 'Markdown'
        ], $data);

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

    public function getTypeText(): string
    {
        return $this->data->get('type_text');
    }

    public function __toString(): string
    {
        return "chat: {$this->getChatId()} :: text: {$this->getText()}";
    }
}
