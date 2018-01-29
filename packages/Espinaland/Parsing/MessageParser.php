<?php

namespace Espinaland\Parsing;

use Illuminate\Support\Collection;
use Espinaland\Support\Objects\MatchedCommand;
use Espinaland\Support\Objects\RequestMessageInterface;

/**
 * Class MessageParser
 * @package Espinaland\Parsing
 */
abstract class MessageParser
{
    protected $matches = [];
    abstract public function getMatches(): array;

    public function parse(RequestMessageInterface $message): Collection
    {
        $matches = collect($this->getMatches())->map(function (array $patterns) use ($message) {
            return $this->firstMatch($message, $patterns);
        });

        return $matches->map(function ($item, $key) {
            return new MatchedCommand($key, $item['match'] ?? '', $item['args']);
        })->filter(function (MatchedCommand $matched) {
            return $matched->hasMatched();
        });
    }

    protected function firstMatch(RequestMessageInterface $message, array $patterns)
    {
        $match = collect($patterns)->first(function ($pattern) use ($message) {
            return $this->matchPattern($pattern, $message, $this->matches);
        });

        return [
            'match' => $match,
            'args'  => $this->getMatchesArgs($this->matches)
        ];
    }

    protected function getMatchesArgs(array $matches = []): array
    {
        return collect($matches)->filter(function ($value, $key) {
            return is_string($key) && !empty($value);
        })->toArray();
    }

    /**
     * @param $pattern
     * @param RequestMessageInterface $message
     * @param array|null $matches
     * @return bool
     */
    protected function matchPattern($pattern, RequestMessageInterface $message, array &$matches = null): bool
    {
        return preg_match($pattern, $message->getTextMessage(), $matches) === 1;
    }
}
