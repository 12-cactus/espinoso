<?php

namespace App\Espinaland\Parsing;

use Illuminate\Support\Collection;
use App\Espinaland\Support\Objects\RequestMessageInterface;

/**
 * Class ParserCollection
 * @package App\Espinaland\Parsing
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
