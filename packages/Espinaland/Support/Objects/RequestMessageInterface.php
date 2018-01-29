<?php

namespace Espinaland\Support\Objects;

/**
 * Interface InputMessageInterface
 * @package App\Objects
 */
interface RequestMessageInterface
{
    public function getChatId(): int;
    public function getTextMessage(): string;
}
