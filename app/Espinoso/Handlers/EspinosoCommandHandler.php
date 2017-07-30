<?php namespace App\Espinoso\Handlers;

abstract class EspinosoCommandHandler extends EspinosoHandler
{
    protected $prefix = '^(espi(noso)?)(\s)+';

    protected function matchCommand($pattern, $updates, array &$matches = null)
    {
        $pattern = "/{$this->prefix}{$pattern}/i";

        return preg_match($pattern, $updates->message->text, $matches);
    }

}