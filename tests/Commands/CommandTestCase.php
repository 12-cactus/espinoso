<?php

namespace Tests\Commands;


use Mockery;
use App\Espinoso;
use Tests\TestCase;
use App\DeliveryServices\TelegramDelivery;

abstract class CommandTestCase extends TestCase
{
    protected $telegram;
    protected $espinoso;

    public function setUp()
    {
        parent::setUp();
        $this->telegram = Mockery::mock(TelegramDelivery::class);
        $this->espinoso = Mockery::mock(Espinoso::class);
        $this->espinoso->shouldReceive('setDelivery')->with($this->telegram);
        $this->appBinding(TelegramDelivery::class, $this->telegram);
    }

    protected function appBindTelegram($instance)
    {
        $this->appBinding(TelegramDelivery::class, $instance);
    }

    protected function appBindEspinoso($instance)
    {
        $this->appBinding(Espinoso::class, $instance);
    }

    protected function appBinding($class, $instance)
    {
        app()->bind($class, function () use ($instance) {
            return $instance;
        });
    }
}
