<?php

namespace Tests\Features;

use Mockery;
use Tests\TestCase;
use Espinarys\Deliveries\TelegramDelivery;
use App\Http\Controllers\TelegramController;
use Espinarys\Builders\TelegramMessageBuilder;
use Espinarys\Parsing\ThornyParsersCollection;


/**
 * Class TelegramSayHiResponseTest
 * @package Tests\Features
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
        $parsers = Mockery::mock(ThornyParsersCollection::class);
        $delivery = resolve(TelegramDelivery::class);
        $message = TelegramMessageBuilder::new()->text('espi cool')->build();
        $parsers->shouldReceive('asRoutes')->with($message->text())->andReturn(collect(['/cool']));
        $delivery->shouldReceive('sendMessage')->once();
        $delivery->shouldReceive('lastMessage')->andReturn($message);

        // Act
        $response = $controller->newHandleUpdates($delivery, $parsers);

        // Assert
        $this->assertEquals(200, $response->getStatusCode());
    }
}
