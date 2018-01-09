<?php namespace Tests\Espinoso\Handlers;

use App\Espinoso\Handlers\StickersHandler;

class StickersHandlerTest extends HandlersTestCase
{
    /**
     * @test
     */
    public function it_should_handle_when_match_regex()
    {
        // Arrange
        $handler = $this->makeHandler();
        $updates = [
            $this->makeMessage([
                'from' => ['id'=> 350079781,'first_name' => 'Facundo'],
                'text' => 'espi maybe']
            ),
            $this->makeMessage([
                'from' => ['id'=> 350079781,'first_name' => 'Facundo'],
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
        $handler = $this->makeHandler();
        $updates = [
            $this->makeMessage([
                    'from' => ['id'=> 000000000,'first_name' => 'Dan'],
                    'text' => 'espi maybe']
            ),
            $this->makeMessage([
                    'from' => ['id'=> 350079781,'first_name' => 'Facundo'],
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
        $sticker = 'CAADAgADiwUAAvoLtgh812FBxEdUAgI'; // LazyPanda

        $this->espinoso->shouldReceive('replySticker')->once()->with($sticker);

        $handler = $this->makeHandler();
        $update = $this->makeMessage([
            'chat' => ['id' => 123],
            'from' => ['id'=> 350079781,'first_name' => 'Facundo'],
            'text' => 'espi maybe'
        ]);

        // Act
        $handler->shouldHandle($update);
        $handler->handle($update);
        $this->assertTrue(true);
    }

    /**
     * @return StickersHandler
     */
    protected function makeHandler(): StickersHandler
    {
        return new StickersHandler($this->espinoso);
    }
}
