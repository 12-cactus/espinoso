<?php

namespace Espinaland\Interpreters;

use Illuminate\Support\Collection;
use Espinaland\Support\Objects\RequestMessageInterface;

/**
 * Class SimplifierCollection
 * @package Espinaland\Parsing
 */
class SimplifierCollection
{
    /**
     * @var Collection
     */
    protected $parsers;

    public function __construct($parsers)
    {
        $this->parsers = collect($parsers)->map(function (string $class) {
            return new $class();
        });
    }

    public function asRoutes(string $message): Collection
    {
        return $this->parsers->map(function (SimplificationInterface $parser) use ($message) {
            return $parser->asRoutes($message);
        })->flatten()->unique();
    }
}
