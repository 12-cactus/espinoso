<?php

namespace Espinaland\Interpreters;

use Illuminate\Support\Collection;

/**
 * Class MessageParser
 * @package Espinaland\Parsing
 */
class FacuTranslator implements SimplificationInterface
{
    protected $matches = [];

    public function asRoutes(string $text): Collection
    {
        return collect(['/salchicha']);
    }
}
