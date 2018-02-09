<?php

namespace Tests\Espinaland\Features;

use App\DeliveryServices\TelegramDelivery;
use App\Http\Controllers\TelegramController;
use App\Http\Middleware\VerifyCsrfToken;
use Espinaland\Interpreters\SimplifierCollection;
use Espinaland\Listening\TelegramListener;
use Mockery;
use Tests\Espinaland\Builders\TelegramMessageBuilder;
use Tests\TestCase;

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
    public function it_says_hi()
    {
        // Arrange
        $this->app->singleton(TelegramDelivery::class, function () {
            $delivery = Mockery::mock(TelegramDelivery::class);
            $delivery->shouldReceive('sendMessage')->once();
            return $delivery;
        });
        $message = TelegramMessageBuilder::new()->text('espi help')->build();
        $listener = Mockery::mock(TelegramListener::class);
        $listener->shouldReceive('lastMessage')->andReturn($message);
        $controller = new TelegramController;
        $simplifier = resolve(SimplifierCollection::class);

        // Act
        $response = $controller->newHandleUpdates($listener, $simplifier);

        // Assert
        $this->assertEquals(200, $response->getStatusCode());
    }
}
