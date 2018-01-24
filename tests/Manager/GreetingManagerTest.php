<?php

namespace Tests\Manager;

use App\Managers\GreetingManager;
use App\Objects\ChatInterface;
use App\Objects\InputMessageInterface;
use Mockery;
use Telegram\Bot\Objects\Chat;
use Tests\TestCase;

/**
 * Class GreetingManagerTest
 * @package Tests\Manager
 */
class GreetingManagerTest extends TestCase
{
    /**
     * @test
     *
     * @return void
     */
    public function it_can_reply_with_hi_message()
    {
        // Arrange
        $chatId  = 12345;
        $message = Mockery::mock(InputMessageInterface::class);
        $message->shouldReceive('getChatId')->andReturn($chatId);
        $manager = new GreetingManager($message);

        // Act
        $output = $manager->sayHi();

        // Assert
        $this->assertEquals($chatId, $output->getChatId());
        $this->assertEquals('How you doing?', $output->getText());
    }
}
