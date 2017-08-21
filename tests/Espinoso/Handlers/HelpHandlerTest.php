<?php namespace Tests\Espinoso\Handlers;

use App\Espinoso\Handlers\HelpHandler;

class HelpHandlerTest extends HandlersTestCase
{
    /**
     * @test
     */
    public function it_should_handle_when_receives_help_message()
    {
        $this->handler = $this->makeHandler();

        $this->assertShouldHandle('espi ayuda');
        $this->assertShouldHandle('espi help!');
        $this->assertShouldHandle('espi aiiiuuuda');
    }

    /**
     * @return HelpHandler
     */
    protected function makeHandler(): HelpHandler
    {
        return new HelpHandler($this->espinoso, $this->delivery);
    }
}
