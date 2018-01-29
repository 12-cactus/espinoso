<?php

namespace App\Espinaland\Ruling;

use Illuminate\Support\Collection;
use App\Espinaland\Support\Objects\MatchedCommand;
use App\Espinaland\Support\Objects\RequestMessageInterface;

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

    public function getRules()
    {
        return $this->rules->getRules();
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
    public function findRules(string $text): Collection
    {
        return $this->rules->getRules()->filter(function (array $item) use ($text) {
            return $item['command'] == $text;
        });
    }

    public function applyRules(Collection $rules, RequestMessageInterface $message): Collection
    {
        $result = $rules->map(function (MatchedCommand $matched) {
            return $this->findRules($matched->getCommand());
        })->flatten(1);

        $responses = $result->map(function ($rule) use ($message) {
            $action = explode('@', $rule['action']);
            $manager = "\\App\\Managers\\{$action[0]}";
            $method  = $action[1];
            $manager = new $manager($message);
            return call_user_func(array($manager, $method));
        });

        return $responses;
    }
}
