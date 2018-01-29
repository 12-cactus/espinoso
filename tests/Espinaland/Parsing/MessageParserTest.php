<?php

namespace Tests\Espinaland\Parsing;

use Mockery;
use Tests\TestCase;
use App\Espinaland\Parsing\MessageParser;
use App\Espinaland\Support\Objects\MatchedCommand;
use App\Espinaland\Support\Objects\RequestMessageInterface;

/**
 * Class MessageParserTest
 * @package Tests\Espinaland\Parsing
 */
class MessageParserTest extends TestCase
{
    /**
     * @test
     *
     * @return void
     */
    public function it_is_an_example_test()
    {
        // Arrange
        $message = Mockery::mock(RequestMessageInterface::class);
        $matchedCommand = Mockery::mock(MatchedCommand::class);
        $expectedMatches = [
            "command" => "espi test {text}",
            "pattern" => "/^espi test (?'text'\w+)/i",
            "args"    => ["text" => "phpunit"]
        ];
        $message->shouldReceive('getTextMessage')->andReturn('espi test phpunit');
        $matchedCommand->shouldReceive('getCommand')->andReturn($expectedMatches['command']);
        $parser = new class extends MessageParser {
            public function getMatches(): array
            {
                return ['espi test {text}' => ["/^espi test (?'text'\w+)/i"]];
            }
        };


        // Act
        $matches = $parser->parse($message);

        // Assert
        $this->assertEquals(1, $matches->count());
        $this->assertEquals($expectedMatches['command'], $matches->first()->getCommand());
    }
}
