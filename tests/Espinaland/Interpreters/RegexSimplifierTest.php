<?php

namespace Tests\Espinaland\Interpreters;

use Tests\TestCase;
use Espinaland\Interpreters\RegexSimplifier;

/**
 * Class FacuTranslatorTest
 * @package Tests\Espinaland\Interpreters
 */
class RegexSimplifierTest extends TestCase
{
    /**
     * @test
     *
     * @return void
     */
    public function it_match_help()
    {
        // Arrange
        $interpreter = new RegexSimplifier;

        // Act
        $routes = $interpreter->asRoutes('espinoso tirame la ayuda');

        // Assert
        $this->assertEquals(['/help'], $routes->toArray());
    }

    /**
     * @test
     *
     * @return void
     */
    public function it_match_get_birthday_of_someone()
    {
        // Arrange
        $interpreter = new RegexSimplifier;

        // Act
        $routes = $interpreter->asRoutes('espi cumple @facu');

        // Assert
        $this->assertEquals(['/cumple/facu'], $routes->toArray());
    }

}
