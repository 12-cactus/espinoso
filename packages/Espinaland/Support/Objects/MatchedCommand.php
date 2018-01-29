<?php

namespace Espinaland\Support\Objects;

/**
 * Class MatchedCommand
 * @package Espinaland\Support\Objects
 */
class MatchedCommand
{
    /**
     * @var string
     */
    protected $command;
    /**
     * @var string
     */
    protected $pattern;
    /**
     * @var array
     */
    protected $args;

    /**
     * MatchedCommand constructor.
     * @param string $command
     * @param string $pattern
     * @param array $args
     */
    public function __construct(string $command, string $pattern, array $args = [])
    {
        $this->command = $command;
        $this->pattern = $pattern;
        $this->args = $args;
    }

    public function hasMatched()
    {
        return !empty($this->pattern);
    }

    public function __toString(): string
    {
        $args = json_encode($this->args);

        return "command: {$this->command} > pattern: {$this->pattern} > args: {$args}";
    }

    /**
     * @return string
     */
    public function getCommand(): string
    {
        return $this->command;
    }
}
