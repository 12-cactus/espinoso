<?php namespace Tests\Feature;

use Tests\Handlers\HandlersTestCase;
use App\Espinoso\Handlers\BardoDelEspinosoHandler;

class BardoDelEspinosoHandlerTest extends HandlersTestCase
{
    /**
     * @test
     */
    public function it_should_handle_when_receives_send_me_nudes()
    {
        $handler = new BardoDelEspinosoHandler($this->telegram);

        $message = $this->makeMessage(['text' => 'send me nudes']);

        $this->assertTrue($handler->shouldHandle($message));
    }

    /**
     * @test
     */
    public function it_should_not_handle_when_receives_another_text()
    {
        $handler = new BardoDelEspinosoHandler($this->telegram);

        $update1 = $this->makeMessage(['text' => 'saraza send me nudes']);
        $update2 = $this->makeMessage(['text' => 'send me nudes saraza']);

        $this->assertFalse($handler->shouldHandle($update1));
        $this->assertFalse($handler->shouldHandle($update2));
    }

    /**
     * @test
     */
    public function it_handle_and_send_photo()
    {
        $photo = [
            'chat_id' => 123,
            'photo'   => 'https://cdn.drawception.com/images/panels/2012/4-4/FErsE1a6t7-8.png',
            'caption' => 'Acá tenés tu nude, hijo de puta!'
        ];
        $this->telegram->shouldReceive('sendPhoto')->once()->with($photo);
        $handler = new BardoDelEspinosoHandler($this->telegram);

        $update = $this->makeMessage([
            'chat' => ['id' => 123],
            'text' => 'send me nudes'
        ]);

        $handler->handle($update);
    }
}
