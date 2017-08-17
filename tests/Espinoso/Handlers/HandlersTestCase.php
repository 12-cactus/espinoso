<?php namespace Tests\Espinoso\Handlers;

use App\Espinoso\Espinoso;
use Mockery;
use Tests\TestCase;
use Telegram\Bot\Api as ApiTelegram;

abstract class HandlersTestCase extends TestCase
{
    protected $handler;
    protected $telegram;
    protected $espinoso;

    protected function setUp()
    {
        parent::setUp();

        $this->espinoso = Mockery::mock(Espinoso::class);
        $this->telegram = Mockery::mock(ApiTelegram::class);
    }

    protected function tearDown()
    {
        Mockery::close();

        parent::tearDown();
    }

    protected function assertShouldHandle($message)
    {
        $this->assertTrue($this->handler->shouldHandle($this->makeMessage(['text' => $message])));
    }

    protected function assertShouldNotHandle($handler, $message)
    {
        $this->assertFalse($handler->shouldHandle($this->makeMessage(['text' => $message])));
    }
}
