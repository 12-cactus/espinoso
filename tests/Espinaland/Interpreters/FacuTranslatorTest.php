<?php

namespace Tests\Espinaland\Interpreters;

use Tests\TestCase;
use Espinaland\Interpreters\FacuTranslator;

/**
 * Class FacuTranslatorTest
 * @package Tests\Espinaland\Interpreters
 */
class FacuTranslatorTest extends TestCase
{
    /**
     * @test
     *
     * @return void
     */
    public function it_is_an_example_test()
    {
        // Arrange
        $interpreter = new FacuTranslator;

        // Act
        $routes1 = $interpreter->asRoutes('cántale a la luna y al sol');
        $routes2 = $interpreter->asRoutes('cantale a la estrella que te acompañó');

        // Assert
        $this->assertEquals(['/salchicha'], $routes1->toArray());
        $this->assertEquals(['/salchicha'], $routes2->toArray());
    }
}
