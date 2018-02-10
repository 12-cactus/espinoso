<?php

namespace Tests\Http\Controllers;

use Mockery;
use App\Espinoso;
use Espinaland\Deliveries\TelegramDelivery;
use App\Http\Controllers\TelegramController;
use Tests\TestCase;
use Telegram\Bot\Api;
use Telegram\Bot\Objects\Chat;
use Telegram\Bot\Objects\User;
use Telegram\Bot\Objects\Update;
use Telegram\Bot\Objects\Message;
use Telegram\Bot\TelegramResponse;

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
    public function it_should_handle_updates()
    {
        // Arrange
        $chatId = 123;
        $text = 'hola espi';
        $espinoso = Mockery::mock(Espinoso::class);
        $telegram = Mockery::mock(TelegramDelivery::class);
        $chat     = Mockery::mock(Chat::class);
        $newUser  = Mockery::mock(User::class);
        $leftUser = Mockery::mock(User::class);
        $update   = Mockery::mock(Update::class);
        $message  = Mockery::mock(Message::class);

        $chat->shouldReceive('getId')->andReturn($chatId);
        $chat->shouldReceive('getFirstName')->once()->andReturnNull();
        $chat->shouldReceive('getTitle')->andReturn('chat');
        $update->shouldReceive('getMessage')->andReturn($message);
        $message->shouldReceive('get')->with('new_chat_participant')->andReturn($newUser);
        $message->shouldReceive('get')->with('left_chat_participant')->andReturn($leftUser);
        $message->shouldReceive('getChat')->andReturn($chat);
        $message->shouldReceive('has')->with('voice')->andReturnTrue();
        $message->shouldReceive('put')->with('text', $text);
        $message->shouldReceive('has')->with('text')->andReturnTrue();
        $message->shouldReceive('getText')->andReturn($text);
        $espinoso->shouldReceive('setDelivery')->once()->with($telegram);
        $espinoso->shouldReceive('isMe')->andReturnTrue();
        $espinoso->shouldReceive('registerChat')->with($chat);
        $espinoso->shouldReceive('deleteChat')->with($chat);
        $espinoso->shouldReceive('sendMessage')
            ->with($chatId, trans('messages.chat.new', ['name' => 'chat']));
        $espinoso->shouldReceive('transcribe')->with($message)->andReturn($text);
        $espinoso->shouldReceive('reply')->with($text);
        $espinoso->shouldReceive('executeHandlers')->with($message);
        $espinoso->shouldReceive('checkIfHasRegisteredChat')->with($chat);
        $telegram->shouldReceive('getUpdate')->once()->andReturn($update);

        // Act
        $controller = new TelegramController;
        $controller->handleUpdates($telegram, $espinoso);

        // Assert
        $this->assertTrue(true);
    }

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
            ->with(['url' => secure_url('new-handle-update')])
            ->andReturn($expectedResponse);

        // Act
        $controller = new TelegramController;
        $response = $controller->setWebhook($telegram);

        // Assert
        $this->assertEquals($expectedResponse, $response);
    }
}
