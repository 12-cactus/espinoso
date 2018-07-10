<?php

namespace App\Parsing;

use Illuminate\Support\Collection;
use Espinarys\Parsing\ThornyRegexParser;

/**
 * Class RegexSimplifier
 * @package App\Parsing
 */
class RegexParser extends ThornyRegexParser
{
    protected function patterns(): Collection
    {
        return collect([
            '/help' => [
                '/^(espi|espinoso)\b(.*)\b(ayuda|help)\b$/i'
            ],
            '/cool' => [
                '/^(espi|espinoso)\b(.*)\b(cool)\b$/i'
            ],
            '/cool-named' => [
                '/^(espi|espinoso)\b(.*)\b(cul)\b$/i'
            ],
            '/cumple/{user}' => [
                "/^(espi|espinoso)(\s+)(cumple)(\s+)@(?'user'\w+)(\s*)$/i"
            ],
        ]);
    }
}
