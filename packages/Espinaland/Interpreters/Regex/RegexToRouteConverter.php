<?php

namespace Espinaland\Interpreters\Regex;

use Illuminate\Support\Collection;

/**
 * Class RegexToRouteConverter
 * @package Espinaland\Interpreters\Regex
 */
class RegexToRouteConverter
{
    /**
     * @var string
     */
    protected $route;
    /**
     * @var Collection
     */
    protected $rules;

    public function __construct(string $route, array $rules = [])
    {
        $this->route = $route;
        $this->rules = collect($rules);
    }

    public function apply(string $text): string
    {
        $matches = [];

        $this->rules->first(function (string $pattern) use ($text, &$matches) {
            return $this->matchPattern($pattern, $text, $matches);
        });

        return empty($matches)
            ? ''
            : $this->parseRoute($matches);
    }

    protected function parseRoute(array $matches = []): string
    {
        $route = $this->route;
        $args  = $this->getMatchesArgs($matches);

        foreach ($args as $key => $value) {
            $route = str_replace('{'.$key.'}', $value, $route);
        }

        return $route;
    }

    protected function getMatchesArgs(array $matches = []): array
    {
        return collect($matches)->filter(function ($value, $key) {
            return is_string($key) && !empty($value);
        })->toArray();
    }

    /**
     * @param $pattern
     * @param $text
     * @param array|null $matches
     * @return bool
     */
    protected function matchPattern($pattern, $text, array &$matches = null): bool
    {
        return preg_match($pattern, $text, $matches) === 1;
    }
}