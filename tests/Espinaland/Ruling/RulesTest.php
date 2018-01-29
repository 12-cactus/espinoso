<?php

namespace Tests\Espinaland\Ruling;

use Tests\TestCase;
use Espinaland\Ruling\Rules;

/**
 * Class RulesTest
 * @package Tests\Espinaland
 */
class RulesTest extends TestCase
{
    /**
     * @test
     *
     * @return void
     */
    public function it_should_find_a_stored_rule()
    {
        // Arrange
        $pattern = new Rules;
        $pattern->match('espi help', 'HelpManager@basic');
        $pattern->match('espi not help', 'HelpManager@nothing');

        // Act
        $patterns = $pattern->findRules('espi help');

        // Assert
        $this->assertEquals(1, $patterns->count());
        $this->assertEquals('espi help', $patterns->first()['command']);
        $this->assertEquals('HelpManager@basic', $patterns->first()['action']);
    }
}
