<?php

namespace Tests\Espinoso\Handlers;

use App\Espinoso\Handlers\GoogleSearchHandler;

class GoogleSearchHandlerTest extends HandlersTestCase
{
    /**
     * @test
     */
    public function it_should_handle_when_match_regex()
    {
        // Arrange
        $handler = $this->makeHandler();
        $update = $this->makeMessage(['text' => 'gg tolkien']);

        $this->assertTrue($handler->shouldHandle($update));
    }

    /**
     * @test
     */
    public function it_should_not_handle_when_receives_another_text()
    {
        // Arrange
        $handler = $this->makeHandler();
        $updates = [
            $this->makeMessage(['text' => 'ggas hola espi']),
            $this->makeMessage(['text' => 'espi gga hola']),
            $this->makeMessage(['text' => 'asgg hola']),
        ];

        // Act && Assert
        collect($updates)->each(function ($update) use ($handler) {
            $this->assertFalse($handler->shouldHandle($update));
        });
    }

    /**
     * @test
     */
/*
    public function it_handle_and_return_info()
    {
        $query = 'tolkien';

        $handler = $this->makeHandler();
        $update = $this->makeMessage(['text' => "gg {$query}"]);


        $handler->shouldHandle($update);
        $handler->handle($update);

    }
*/
    /**
     * @return GoogleSearchHandler
     */
    protected function makeHandler(): GoogleSearchHandler
    {
        return new GoogleSearchHandler($this->espinoso);
    }
}