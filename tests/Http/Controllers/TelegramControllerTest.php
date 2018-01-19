<?php

namespace Tests\Http\Controllers;

use Mockery;
use Tests\TestCase;
use Telegram\Bot\Api;
use Telegram\Bot\TelegramResponse;
use App\Http\Controllers\TelegramController;

/**
 * Class TelegramControllerTest
 * @package Tests\Http\Controllers
 */
class TelegramControllerTest extends TestCase
{
//    use RefreshDatabase;

    /**
     * @test
     *
     * @return void
     */
    public function it_should_set_webhook()
    {
        // Arrange
        $expectedResponse = Mockery::mock(TelegramResponse::class);
        $telegram = Mockery::mock(Api::class);
        $telegram->shouldReceive('setWebhook')
            ->with(['url' => secure_url('handle-update')])
            ->andReturn($expectedResponse);

        // Act
        $controller = new TelegramController;
        $response = $controller->setWebhook($telegram);

        // Assert
        $this->assertEquals($expectedResponse, $response);
    }
}
