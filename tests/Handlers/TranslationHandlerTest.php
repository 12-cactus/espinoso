<?php

namespace Tests\Handlers;

use App\Espinoso\Handlers\TranslationHandler;
use App\Facades\Translator;

class TranslationHandlerTest extends HandlersTestCase
{
    /**
     * @test
     */
   public function it_should_handle_when_match_regex()
    {
        // Arrange
        $handler = $this->makeHandler();
        $update = $this->makeMessage(['text' => 'gt fuck you']);

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
            $this->makeMessage(['text' => 'gtas hola espi']),
            $this->makeMessage(['text' => 'espi gta hola']),
            $this->makeMessage(['text' => 'asgt hola']),
        ];

        // Act && Assert
        collect($updates)->each(function ($update) use ($handler) {
            $this->assertFalse($handler->shouldHandle($update));
        });
    }

    /**
     * @test
     */
        public function it_handle_and_return_info()
        {
            $query = 'the winter is coming';
            $translation = "viene el invierno";

            $handler = $this->makeHandler();
            $update = $this->makeMessage(['text' => "gt {$query}"]);

            Translator::shouldReceive('translate')->with($query)->andReturn($translation);
            $this->espinoso->shouldReceive('reply')->once()->with($translation);

            $handler->shouldHandle($update);
            $handler->handle($update);
            $this->assertTrue(true);
        }

    /**
     * @return TranslationHandler
     */
    protected function makeHandler(): TranslationHandler
    {
        return new TranslationHandler($this->espinoso);
    }
}