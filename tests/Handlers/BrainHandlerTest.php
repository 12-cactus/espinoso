<?php

namespace Tests\Handlers;

use Mockery;
use Spatie\Emoji\Emoji;
use App\Espinoso\Handlers\BrainHandler;

/**
 * Class BrainHandlerTest
 * @package Tests\Espinoso\Handlers
 */
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
        $this->shouldRespondWith('macri',    'Gato ' . Emoji::catFaceWithWrySmile());
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
        return new BrainHandler($this->espinoso);
    }

    /**
     * @param string $text
     * @param string $response
     */
    protected function shouldRespondWith(string $text, string $response)
    {
        $this->shouldReceiveReplyWith($text, $response);
    }

    /**
     * @param string $text
     * @param array $outs
     */
    protected function shouldRespondWithAny(string $text, array $outs = [])
    {
        $this->shouldReceiveReplyWith($text, Mockery::any($outs));
    }

    /**
     * @param string $text
     * @param $responses
     */
    private function shouldReceiveReplyWith(string $text, $responses)
    {
        $message = $this->text($text);

        $this->espinoso->shouldReceive('reply')->with($responses);

        $this->handler = $this->makeHandler();
        $this->assertTrue($this->handler->shouldHandle($message));
        $this->handler->handle($message);
    }
}
