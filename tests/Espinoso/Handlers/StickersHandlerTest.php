<?php namespace Tests\Espinoso\Handlers;

use App\Espinoso\Handlers\StickersHandler;

class NextHolidaysHandlerTest extends HandlersTestCase
{
    /**
     * @test
     */
    public function it_should_handle_when_match_regex()
    {
        // Arrange
        $handler = new StickersHandler($this->telegram);
        $updates = [
            $this->makeMessage([
                'from' => ['first_name' => 'Facundo'],
                'text' => 'espi maybe']
            ),
            $this->makeMessage([
                'from' => ['first_name' => 'Facundo'],
                'text' => 'maybe']
            ),
        ];

        // Act && Assert
        collect($updates)->each(function ($update) use ($handler) {
            $this->assertTrue($handler->shouldHandle($update));
        });
    }

    /**
     * @test
     */
    public function it_should_not_handle_when_receives_another_text()
    {
        // Arrange
        $handler = new StickersHandler($this->telegram);
        $updates = [
            $this->makeMessage([
                    'from' => ['first_name' => 'Dan'],
                    'text' => 'espi maybe']
            ),
            $this->makeMessage([
                    'from' => ['first_name' => 'Facundo'],
                    'text' => 'espimaybe']
            ),
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
        // Mocking
        $message = [
            'chat_id' => 123,
            'sticker' => 'CAADAgADiwUAAvoLtgh812FBxEdUAgI' // LazyPanda
        ];
        $this->telegram->shouldReceive('sendSticker')->once()->with($message);

        $handler = new StickersHandler($this->telegram);
        $update = $this->makeMessage([
            'chat' => ['id' => 123],
            'from' => ['first_name' => 'Facundo'],
            'text' => 'espi maybe'
        ]);

        // Act
        $handler->shouldHandle($update);
        $handler->handle($update);
    }
}
