<?php

namespace App\Espinaland\Ruling;

use Illuminate\Support\Collection;

/**
 * Class Rules
 * @package App\Espinaland
 */
class Rules
{
    /**
     * @var RuleCollection
     */
    protected $rules;

    /**
     * Rules constructor.
     */
    public function __construct()
    {
        $this->rules = new RuleCollection;
    }

    /**
     * Add a match to the collection of rules
     *
     * @param string $command
     * @param string $action
     * @return $this
     */
    public function match(string $command, string $action)
    {
        $this->rules->add(compact('command', 'action'));

        return $this;
    }

    /**
     * Find rules that match with $text
     *
     * @param string $text
     * @return Collection
     */
    public function findRulesTo(string $text): Collection
    {
        return $this->rules->getRules()->filter(function (array $item) use ($text) {
            return $item['command'] == $text;
        });
    }
}
