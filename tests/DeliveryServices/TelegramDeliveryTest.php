<?php

namespace Tests\DeliveryServices;

use Mockery;
use App\Facades\GuzzleClient;
use App\DeliveryServices\TelegramDelivery;
use Tests\TestCase;
use Telegram\Bot\Api;
use Telegram\Bot\Objects\File;
use Telegram\Bot\Objects\Voice;
use Telegram\Bot\Objects\Update;
use Telegram\Bot\Objects\Message;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\ResponseInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class TelegramDeliveryTest
 * @package Tests\DeliveryServices
 */
class TelegramDeliveryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     *
     * @return void
     */
    public function it_returns_last_update()
    {
        // Arrange
        $data = ['message' => new Message('hi there')];
        $api = Mockery::mock(Api::class);
        $mockedUpdate = Mockery::mock(Update::class);
        $mockedUpdate->shouldReceive('getRawResponse')->andReturn($data);
        $api->shouldReceive('getWebhookUpdates')->andReturn($mockedUpdate);
        $delivery = $this->makeDelivery($api);

        // Act
        $update = $delivery->getUpdate();

        // Assert
        $this->assertEquals($data, $update->getRawResponse());
        $this->assertEquals($data['message']->all(), $update->getMessage()->all());
    }

    /**
     * @test
     *
     * @return void
     */
    public function it_send_messages(): void
    {
        // Arrange
        $params = $this->makeMessage()->getRawResponse();
        $api = Mockery::mock(Api::class);
        $api->shouldReceive('sendMessage')->once()->with($params);
        $api->shouldReceive('sendPhoto')->once()->with($params);
        $api->shouldReceive('sendSticker')->once()->with($params);
        $api->shouldReceive('sendDocument')->once()->with($params);
        $delivery = $this->makeDelivery($api);

        // Act
        $delivery->sendMessage($params);
        $delivery->sendImage($params);
        $delivery->sendSticker($params);
        $delivery->sendGif($params);

        // Assert
        $this->assertTrue(true);
    }

    /**
     * @test
     *
     * @return void
     */
    public function it_get_file_url(): void
    {
        // Arrange
        $urlExpected = 'http://espinoso.mocking';
        $params = $this->makeMessage()->getRawResponse();
        $file = Mockery::mock(File::class);
        $file->shouldReceive('getFilePath')->once()->andReturn($urlExpected);
        $api = Mockery::mock(Api::class);
        $api->shouldReceive('getFile')->once()->with($params)->andReturn($file);
        $delivery = $this->makeDelivery($api);

        // Act
        $url = $delivery->getFileUrl($params);

        // Assert
        $this->assertEquals($urlExpected, $url);
    }

    /**
     * @test
     *
     * @return void
     */
    public function it_get_voice_stream(): void
    {
        // Arrange
        $url = 'http://espinoso.mocking';
        $params = ['file_id' => 'ABC123'];
        $streamExpected = Mockery::mock(StreamInterface::class);
        $voice = new Voice($params);
        $file = Mockery::mock(File::class);
        $file->shouldReceive('getFilePath')->once()->andReturn($url);
        $api = Mockery::mock(Api::class);
        $api->shouldReceive('getFile')->once()->with($params)->andReturn($file);
        $response = Mockery::mock(ResponseInterface::class);
        $response->shouldReceive('getBody')->andReturn($streamExpected);
        GuzzleClient::shouldReceive('get')
            ->withArgs([config('espinoso.telegram.url.file')."{$url}", ['stream' => true]])
            ->andReturn($response);
        $delivery = $this->makeDelivery($api);

        // Act
        $stream = $delivery->getVoiceStream($voice);

        // Assert
        $this->assertEquals($streamExpected, $stream);
    }

    /**
     * @test
     *
     * @return void
     */
    public function it_register_and_delete_chat(): void {
        // Arrange
        $message = $this->makeMessage();
        $chat = $message->getChat();
        $api = Mockery::mock(Api::class);
        $delivery = $this->makeDelivery($api);

        // Act
        $isNew = $delivery->registerChat($chat);

        // Assert
        $this->assertTrue($isNew);
        $this->assertDatabaseHas('telegram_chats', [
            'id' => $chat->getId(),
            'type' => $chat->getType(),
            'title' => $chat->getTitle(),
        ]);

        // Re-act
        $delivery->deleteChat($chat);

        // Re-assert
        $this->assertDatabaseMissing('telegram_chats', [
            'id' => $chat->getId(),
            'type' => $chat->getType(),
            'title' => $chat->getTitle(),

        ]);
    }
    
    /**
     * @test
     * 
     * @return void
     */
    public function it_check_if_chat_was_registered(): void
    {
        // Arrange
        $chat = $this->makeMessage()->getChat();
        $api = Mockery::mock(Api::class);
        $delivery = $this->makeDelivery($api);
        
        // Act
        $registered = $delivery->hasRegisteredChat($chat);

        // Assert
        $this->assertFalse($registered);

        // Re-act
        $delivery->registerChat($chat);
        $registered = $delivery->hasRegisteredChat($chat);

        // Re-assert
        $this->assertTrue($registered);
    }

    /**
     * @test
     *
     * @return void
     */
    public function it_check_if_user_is_bot_itself(): void
    {
        // Arrange
        $user = $this->makeMessage()->getFrom();
        $api = Mockery::mock(Api::class);
        $api->shouldReceive('getMe')->andReturn($user);
        $delivery = $this->makeDelivery($api);

        // Act
        $isMe = $delivery->isMe($user);

        // Assert
        $this->assertTrue($isMe);
    }

    /*
     * Internals
     */

    protected function makeDelivery($api)
    {

        return new TelegramDelivery($api);
    }
}
