<?php

namespace Espinaland\Interpreters;

use Illuminate\Support\Collection;

/**
 * Interface SimplificationInterface
 * @package Espinaland\Interpreters
 */
interface SimplificationInterface
{
    public function asRoutes(string $text): Collection;
}
