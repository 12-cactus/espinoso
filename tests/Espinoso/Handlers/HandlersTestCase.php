<?php namespace Tests\Espinoso\Handlers;

use Mockery;
use Tests\TestCase;
use App\Espinoso\Espinoso;
use App\Espinoso\DeliveryServices\EspinosoDeliveryInterface;

abstract class HandlersTestCase extends TestCase
{
    protected $handler;
    protected $delivery;
    protected $espinoso;

    protected function setUp()
    {
        parent::setUp();

        $this->espinoso = Mockery::mock(Espinoso::class);
        $this->delivery = Mockery::mock(EspinosoDeliveryInterface::class);
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
