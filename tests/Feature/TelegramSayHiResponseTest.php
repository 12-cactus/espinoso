<?php

namespace Tests\Espinaland\Features;

use Mockery;
use Tests\TestCase;
use Espinaland\Deliveries\TelegramDelivery;
use App\Http\Controllers\TelegramController;
use Espinaland\Interpreters\SimplifierCollection;
use Tests\Espinaland\Builders\TelegramMessageBuilder;

/**
 * Class TelegramSayHiResponseTest
 * @package Tests\Espinaland\Features
 */
class TelegramSayHiResponseTest extends TestCase
{
    /**
     * @test
     *
     * @return void
     */
    public function it_says_cool()
    {
        // Arrange
        $this->app->singleton(TelegramDelivery::class, function () {
            return Mockery::mock(TelegramDelivery::class);
        });
        $controller = new TelegramController;
        $simplifier = resolve(SimplifierCollection::class);
        $delivery = resolve(TelegramDelivery::class);
        $message = TelegramMessageBuilder::new()->text('espi cool')->build();
        $delivery->shouldReceive('sendMessage')->once();
        $delivery->shouldReceive('lastMessage')->andReturn($message);

        // Act
        $response = $controller->newHandleUpdates($delivery, $simplifier);

        // Assert
        $this->assertEquals(200, $response->getStatusCode());
    }
}
