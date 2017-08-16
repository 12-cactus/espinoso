<?php namespace Tests\Espinoso\Handlers;

use App\Espinoso\Handlers\BrainHandler;
use App\Facades\GoutteClient;
use Mockery;

class BrainHandlerTest extends HandlersTestCase
{
    /**
     * @test
     */
    public function it_should_respond_this()
    {
        $this->handler = $this->makeHandler();


        $this->assertShouldHandle('macri');
        $this->assertShouldHandle('espi');
        $this->assertShouldHandle('espi espi espi');
        $this->assertShouldHandle('espinoso');
        $this->assertShouldHandle('espiiiii');
        $this->assertShouldHandle('espi!!!!');
        $this->assertShouldHandle('espiii!!');
        $this->assertShouldHandle('asado');
        $this->assertShouldHandle('marcos');
        $this->assertShouldHandle('maximo');
        $this->assertShouldHandle('facu');
        $this->assertShouldHandle('jajajajaja');
        $this->assertShouldHandle('fuck');
        $this->assertShouldHandle('mamu');
        $this->assertShouldHandle('jarvis');
        $this->assertShouldHandle('hola espinoso');
        $this->assertShouldHandle('papu');
        $this->assertShouldHandle('ponerla');
        $this->assertShouldHandle('contrato');
        $this->assertShouldHandle('maldicion');
        $this->assertShouldHandle('concha de la lora');
        $this->assertShouldHandle('dan el tip');
        $this->assertShouldHandle('empanada');
        $this->assertShouldHandle('ayuda gsm');
    }

    /**
     * @test
     */
    public function it_should_respond_with_concrete_message()
    {
        $this->shouldRespondWith('macri',    'Gato');
        $this->shouldRespondWith('espi',     'Otra vez rompiendo los huevos... Que pija quieren?');
        $this->shouldRespondWith('empanada', 'mmmm de carne y bien jugosa');
    }

    /**
      * @test
      */
    public function it_should_respond_with_any_of_messages()
    {
        $this->shouldRespondWithAny('alan', [
            'Alan lo hace por dinero',
            'acaso dijiste $$$ oriented programming?',
        ]);

        $this->shouldRespondWithAny('ines', [
            'esa Ines esa una babosa, siempre mirando abs',
            'Ine es una niñita sensible e inocente!', 'Ine te deja sin pilas'
        ]);

    }

    /**
     * @return BrainHandler
     */
    protected function makeHandler(): BrainHandler
    {
        return new BrainHandler($this->espinoso, $this->telegram);
    }

    protected function shouldRespondWith(string $in, string $out)
    {
        $message = $this->text($in);

        $response = [
            'chat_id' => $message->getChat()->getId(),
            'text'    => $out,
            'parse_mode' => 'Markdown'
        ];

        $this->telegram->shouldReceive('sendMessage')->with($response);

        $this->handler = $this->makeHandler();
        $this->assertTrue($this->handler->shouldHandle($message));
        $this->handler->handle($message);
    }

    protected function shouldRespondWithAny(string $in, array $outs = [])
    {
        $message = $this->text($in);

        $responses = collect($outs)->map(function ($out) use ($message) {
            return [
                'chat_id' => $message->getChat()->getId(),
                'text'    => $out,
                'parse_mode' => 'Markdown'
            ];
        })->toArray();

        $this->telegram
            ->shouldReceive('sendMessage')
            ->with(Mockery::any($responses));

        $this->handler = $this->makeHandler();
        $this->assertTrue($this->handler->shouldHandle($message));
        $this->handler->handle($message);
    }
}
