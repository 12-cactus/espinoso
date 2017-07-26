<?php namespace Tests\Feature;

use Telegram\Bot\Exceptions\TelegramResponseException;
use Tests\Handlers\HandlersTestCase;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\Espinoso\Handlers\BardoDelEspinosoHandler;

class BardoDelEspinosoHandlerTest extends HandlersTestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->handler = new BardoDelEspinosoHandler;
    }

    /**
     * @test
     */
    public function it_should_handle_when_receives_send_me_nudes()
    {
        $update = $this->update(['text' => 'send me nudes']);

        $this->assertTrue($this->handler->shouldHandle($update));
    }

    /**
     * @test
     */
    public function it_should_not_handle_when_receives_another_text()
    {
        $update1 = $this->update(['text' => 'saraza send me nudes']);
        $update2 = $this->update(['text' => 'send me nudes saraza']);

        $this->assertFalse($this->handler->shouldHandle($update1));
        $this->assertFalse($this->handler->shouldHandle($update2));
    }

    /**
     * @test
     */
    public function it_handle_and_send_photo()
    {
        $photo = [
            'chat_id' => 123,
            'photo'   => 'https://cdn.drawception.com/images/panels/2012/4-4/FErsE1a6t7-8.png',
            'caption' => 'Acá tenés tu nude, puto del orto!'
        ];
        Telegram::shouldReceive('sendPhoto')->once()->with($photo);

        $update = $this->update([
            'chat' => ['id' => 123],
            'text' => 'send me nudes'
        ]);

        $this->handler->handle($update);
    }
}
