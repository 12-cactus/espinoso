<?php namespace Tests\Espinoso\Handlers;

use App\Espinoso\Handlers\BrainHandler;

class BrainHandlerTest extends HandlersTestCase
{
    /**
     * @test
     */
    public function should_handle_test()
    {
        // Arrange
        $handler = $this->makeHandler();
        $messages = collect([
            $this->makeMessage(['text' => 'macri']),
            $this->makeMessage(['text' => 'espi']),
            $this->makeMessage(['text' => 'espi espi espi']),
            $this->makeMessage(['text' => 'espinoso']),
            $this->makeMessage(['text' => 'espiiiii']),
            $this->makeMessage(['text' => 'espi!!!!']),
            $this->makeMessage(['text' => 'espiii!!']),
        ]);

        // Act && Assert
        $messages->each(function ($message) use ($handler) {
            $this->assertTrue($handler->shouldHandle($message));
        });
    }

    /**
     * @return BrainHandler
     */
    protected function makeHandler(): BrainHandler
    {
        return new BrainHandler($this->espinoso, $this->telegram);
    }
}
