<?php namespace Tests\Espinoso\Handlers;

use App\Espinoso\Handlers\TraductorHandler;

class TraductorHandlerTest extends HandlersTestCase
{
    /**
     * @test
     */
   public function it_should_handle_when_match_regex()
    {
        // Arrange
        $handler = $this->makeHandler();
        $update = $this->makeMessage(['text' => 'gt fuckyou']);

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

            $handler = $this->makeHandler();
            $update = $this->makeMessage(['text' => 'gt the winter is comming']);

            $text = "El invierno esta llegando";
            $this->espinoso->shouldReceive('reply')->once()->with($text);

            $handler->shouldHandle($update);
            $handler->handle($update);

            $this->assertTrue($handler->shouldHandle($update),'El invierno esta llegando');
        }

    /**
     * @return TraductorHandler
     */
    protected function makeHandler(): TraductorHandler
    {
        return new TraductorHandler($this->espinoso);
    }
}