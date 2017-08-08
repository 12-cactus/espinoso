<?php namespace Tests\Handlers;

use Mockery;
use Tests\TestCase;
use Telegram\Bot\Api as ApiTelegram;

abstract class HandlersTestCase extends TestCase
{
    protected $telegram;

    protected function setUp()
    {
        parent::setUp();

        $this->telegram = Mockery::mock(ApiTelegram::class);
    }

    protected function tearDown()
    {
        Mockery::close();

        parent::tearDown();
    }

    protected function assertShouldHandle($handler, $message)
    {
        $this->assertTrue($handler->shouldHandle($this->makeMessage(['text' => $message])));
    }

    protected function assertShouldNotHandle($handler, $message)
    {
        $this->assertFalse($handler->shouldHandle($this->makeMessage(['text' => $message])));
    }
}
