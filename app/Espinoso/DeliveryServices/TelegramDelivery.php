<?php namespace App\Espinoso\DeliveryServices;

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
}
