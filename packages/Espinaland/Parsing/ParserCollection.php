<?php

namespace Espinaland\Parsing;

use Illuminate\Support\Collection;
use Espinaland\Support\Objects\RequestMessageInterface;

/**
 * Class ParserCollection
 * @package Espinaland\Parsing
 */
class ParserCollection
{
    /**
     * @var Collection
     */
    protected $parsers;

    public function __construct($parsers)
    {
        $this->parsers = collect($parsers)->map(function ($class) {
            return new $class();
        });
    }

    public function parse(RequestMessageInterface $message): Collection
    {
        return $this->parsers->map(function (MessageParser $parser) use ($message) {
            return $parser->parse($message);
        })->flatten();
    }
}
