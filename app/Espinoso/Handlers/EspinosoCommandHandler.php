<?php namespace App\Espinoso\Handlers;

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
     * @param $updates
     * @param array|null $matches
     * @return int
     */
    protected function matchCommand($pattern, $updates, array &$matches = null)
    {
        $quantifier = $this->allow_ignore_prefix ? '?' : '{1,3}';
        $text = $this->isTextMessage($updates) ? $updates->message->text : '';

        return preg_match(
            "/{$this->prefix_regex}{$quantifier}{$pattern}/{$this->flags}",
            $text,
            $matches
        );
    }

}
