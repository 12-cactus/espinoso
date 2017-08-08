<?php namespace App\Espinoso\Handlers;

use Telegram\Bot\Objects\Message;

abstract class EspinosoCommandHandler extends EspinosoHandler
{
    protected $flags = 'i';
    protected $prefix_regex = "^(?'e'espi(noso)?\s+)"; // 'espi|espinoso '
    /**
     * @var bool
     * If false, should match 'espi'
     * If true, could not match 'espi'
     */
    protected $allow_ignore_prefix = false;

    /**
     * @param $pattern
     * @param Message $message
     * @param array|null $matches
     * @return bool
     */
    protected function matchCommand($pattern, Message $message, array &$matches = null): bool
    {
        $quantifier = $this->allow_ignore_prefix ? '?' : '{1,3}';
        $text = $message->getText();
        $pattern = "/{$this->prefix_regex}{$quantifier}{$pattern}/{$this->flags}";

        return preg_match($pattern, $text, $matches) === 1;
    }

}
