<?php

namespace Tests\Feature;

use App\Espinoso\DeliveryServices\TelegramDelivery;
use App\Model\TelegramChat;
use Telegram\Bot\Objects\Chat;

/**
 * Class RegisterChatTest
 * @package Tests\Feature
 *
 * Cases:
 *
 * - User start a private chat for first time
 * - User delete private chat
 * - User start a private chat again after delete
 * - User stop & block private chat
 * - Group add bot to group chat for first time
 * - Group remove bot from group
 * - Group add bot to group chat again
 */
class RegisterTelegramChatTest extends FeatureTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->delivery = resolve(TelegramDelivery::class);
        $this->espinoso->setDelivery($this->delivery);
    }

    /**
     * @test
     * User start a private chat for first time
     *
     * @return void
     */
    public function when_user_start_private_chat_for_first_time_then_chat_is_persisted()
    {
        // Arrange
        $chat = factory(TelegramChat::class)->states('private')->make()->getAttributes();
        $chat = new Chat($chat);
        $this->assertDatabaseMissing('telegram_chats', [
            'id' => $chat->getId(),
        ]);

        // Act
        $isNew = $this->delivery->registerChat($chat);

        // Assert
        $this->assertTrue($isNew);
        $this->assertDatabaseHas('telegram_chats', [
            'id' => $chat->getId(),
            'type' => $chat->getType(),
            'username' => $chat->getUsername(),
            'first_name' => $chat->getFirstName(),
        ]);
    }
}
