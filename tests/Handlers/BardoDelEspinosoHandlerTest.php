<?php

namespace Tests\Handlers;

use App\Espinoso\Handlers\BardoDelEspinosoHandler;

class BardoDelEspinosoHandlerTest extends HandlersTestCase
{
    /**
     * @test
     */
    public function it_should_handle_when_receives_send_me_nudes()
    {
        // Arrange
        $handler = $this->makeHandler();
        $message = $this->makeMessage(['text' => 'send me nudes']);

        // Act && Assert
        $this->assertTrue($handler->shouldHandle($message));
    }

    /**
     * @test
     */
    public function it_should_not_handle_when_receives_another_text()
    {
        // Arrange
        $handler = $this->makeHandler();
        $update1 = $this->makeMessage(['text' => 'saraza send me nudes']);
        $update2 = $this->makeMessage(['text' => 'send me nudes saraza']);

        // Act & Assert
        $this->assertFalse($handler->shouldHandle($update1));
        $this->assertFalse($handler->shouldHandle($update2));
    }

    /**
     * @test
     */
    public function it_handle_and_send_photo()
    {
        // Mocking
        $photo = 'https://cdn.drawception.com/images/panels/2012/4-4/FErsE1a6t7-8.png';
        $caption = 'Acá tenés tu nude, hijo de puta!';
        $this->espinoso->shouldReceive('replyImage')->once()->with($photo, $caption);

        // Arrange
        $handler = $this->makeHandler();
        $update = $this->makeMessage(['text' => 'send me nudes']);

        // Act
        $handler->shouldHandle($update);
        $handler->handle();
        $this->assertTrue(true);
    }

    /**
     * @return BardoDelEspinosoHandler
     */
    protected function makeHandler(): BardoDelEspinosoHandler
    {
        return new BardoDelEspinosoHandler($this->espinoso);
    }
}
