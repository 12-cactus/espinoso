<?php

namespace Tests\Commands;


use Mockery;

class HiCommandTest extends CommandTestCase
{
    /**
     * @test
     */
    public function testHiCommand()
    {
        $messages = collect(__('messages.hi'));
        $this->espinoso->shouldReceive('sendToCactus')->with(Mockery::any($messages));
        $this->appBindEspinoso($this->espinoso);

        $this->artisan('espi:hi')->assertExitCode(0);
    }
}
