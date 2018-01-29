<?php

namespace App\Parsers;

use Espinaland\Parsing\MessageParser;

/**
 * Class SampleParser
 * @package App\Parsers
 */
class SampleParser extends MessageParser {
    public function getMatches(): array
    {
        return [
            'espi test {text}' => [
                "/^espi test (?'text'\w+)$/i"
            ],
            'espi hi' => [
                "/^espi hi$/i"
            ],
        ];
    }
}
