<?php

namespace Espinaland\Ruling;

use Countable;
use ArrayIterator;
use IteratorAggregate;
use Illuminate\Support\Collection;

/**
 * Class RuleCollection
 * @package Espinaland\Ruling
 *
 * @inspired by \Illuminate\Routing\RouteCollection
 */
class RuleCollection implements Countable, IteratorAggregate
{
    /**
     * An array of the rules.
     *
     * @var Collection
     */
    protected $rules;

    /**
     * RuleCollection constructor.
     */
    public function __construct()
    {
        $this->rules = new Collection;
    }

    /**
     * Add a Route instance to the collection.
     *
     * @param array $rule
     */
    public function add(array $rule = []): void
    {
        $this->rules->push($rule);
    }

    /**
     * Get all of the rules in the collection.
     *
     * @return Collection
     */
    public function getRules(): Collection
    {
        return $this->rules;
    }

    /**
     * Get an iterator for the items.
     *
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return $this->getRules()->getIterator();
    }

    /**
     * Count the number of items in the collection.
     *
     * @return int
     */
    public function count()
    {
        return $this->getRules()->count();
    }
}
