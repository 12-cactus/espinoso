<?php namespace Tests\Espinoso\Handlers;

use App\Espinoso\Espinoso;
use App\Espinoso\Handlers\StartCommandHandler;
use Mockery;
use App\Facades\GoutteClient;
use Symfony\Component\DomCrawler\Crawler;
use Telegram\Bot\Objects\Chat;

class StartCommandHandlerTest extends HandlersTestCase
{
    /**
     * @test
     */
    public function it_should_handle_when_match_regex()
    {
        // Arrange
        $handler = $this->makeHandlerWith($this->espinoso);
        $updates = [
            $this->makeMessage(['text' => 'start']),
            $this->makeMessage(['text' => 'start    ']),
        ];

        // Act & Assert
        collect($updates)->each(function ($update) use ($handler) {
            $this->assertTrue($handler->shouldHandle($update));
        });
    }

    /**
     * @test
     */
    public function it_should_not_handle_when_receives_another_text()
    {
        // Arrange
        $handler = $this->makeHandlerWith($this->espinoso);
        $updates = [
            $this->makeMessage(['text' => '/start']), // because is parsed
            $this->makeMessage(['text' => 'started']),
            $this->makeMessage(['text' => 'el negro del wasap']),
        ];

        // Act & Assert
        collect($updates)->each(function ($update) use ($handler) {
            $this->assertFalse($handler->shouldHandle($update));
        });
    }

    /**
     * @test
     */
    public function it_should_reply_with_a_welcome_message_when_new_private_chat_is_started()
    {
        // Assert
        $update = $this->makeMessage(['text' => 'start']);
        $replyText = trans('messages.chat.new', ['name' => $update->getFrom()->getFirstName()]);

        // Mocking
        $this->espinoso
            ->shouldReceive('registerChat')
            ->once()
            ->with(Mockery::type(Chat::class))
            ->andReturn(true);
        $this->espinoso
            ->shouldReceive('reply')
            ->once()
            ->with($replyText);

        // Act
        $handler = $this->makeHandlerWith($this->espinoso);
        $handler->shouldHandle($update);
        $handler->handle();
    }

    /**
     * @test
     */
    public function it_should_reply_with_a_common_message_when_message_is_start_again()
    {
        // Assert
        $update = $this->makeMessage(['text' => 'start']);
        $replyText = trans('messages.chat.new-again');

        // Mocking
        $this->espinoso
            ->shouldReceive('registerChat')
            ->once()
            ->with(Mockery::type(Chat::class))
            ->andReturn(false);
        $this->espinoso
            ->shouldReceive('reply')
            ->once()
            ->with($replyText);

        // Act
        $handler = $this->makeHandlerWith($this->espinoso);
        $handler->shouldHandle($update);
        $handler->handle();
    }



    /**
     * @param Espinoso $espinoso
     * @return StartCommandHandler
     */
    protected function makeHandlerWith(Espinoso $espinoso): StartCommandHandler
    {
        return new StartCommandHandler($espinoso);
    }
}
