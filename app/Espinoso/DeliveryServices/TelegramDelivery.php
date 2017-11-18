<?php namespace App\Espinoso\DeliveryServices;

use App\Model\TelegramChat;
use Telegram\Bot\Objects\Chat;
use Telegram\Bot\Objects\Message;
use Telegram\Bot\Api as ApiTelegram;

/**
 * Class TelegramDelivery
 * @package App\Espinoso\DeliveryServices
 */
class TelegramDelivery implements EspinosoDeliveryInterface
{
    /**
     * @var ApiTelegram
     */
    protected $telegram;

    /**
     * TelegramDelivery constructor.
     * @param ApiTelegram $telegram
     */
    public function __construct(ApiTelegram $telegram)
    {
        $this->telegram = $telegram;
    }

    /**
     * @return Message
     */
    public function getMessage(): Message
    {
        $update = $this->telegram->getWebhookUpdates();
        logger($update);

        return $update->getMessage() ?? new Message($update['edited_message']);
    }

    /**
     * @param array $params
     */
    public function sendMessage(array $params = []): void
    {
        $this->telegram->sendMessage($params);
    }

    /**
     * @param array $params
     */
    public function sendImage(array $params = []): void
    {
        $this->telegram->sendPhoto($params);
    }

    /**
     * @param array $params
     */
    public function sendSticker(array $params = []): void
    {
        $this->telegram->sendSticker($params);
    }

    public function sendGif(array $params = []): void
    {
        $this->telegram->sendDocument($params);
    }

    /**
     * Register chat and return true if new
     *
     * @param Chat $chat
     * @return bool
     */
    public function registerChat(Chat $chat): bool
    {
        $isNew = empty(TelegramChat::find($chat->getId()));

        TelegramChat::updateOrCreate([
            'id' => $chat->getId(),
            'first_name' => $chat->getFirstName(),
            'last_name' => $chat->getLastName(),
            'username' => $chat->getUsername(),
            'type' => $chat->getType(),
        ]);

        return $isNew;
    }
}
