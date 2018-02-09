<?php

namespace Espinaland\Interpreters;

use Illuminate\Support\Collection;
use Espinaland\Interpreters\Regex\RegexToRouteConverter;

/**
 * Class RegexSimplifier
 * @package Espinaland\Interpreters
 */
class RegexSimplifier implements SimplificationInterface
{
    public function asRoutes(string $text): Collection
    {
        return $this->patterns()->map(function ($rules, $route) use ($text) {
            $converter = new RegexToRouteConverter($route, $rules);
            return $converter->apply($text);
        })->filter()->values()->unique();
    }

    protected function patterns(): Collection
    {
        return collect([
            '/help' => [
                '/^(espi|espinoso)\b(.*)\b(ayuda|help)\b$/i'
            ],
            '/cool' => [
                '/^(espi|espinoso)\b(.*)\b(cool)\b$/i'
            ],
            '/cumple/{user}' => [
                "/^(espi|espinoso)(\s+)(cumple)(\s+)@(?'user'\w+)(\s*)$/i"
            ],
        ]);
    }
}
