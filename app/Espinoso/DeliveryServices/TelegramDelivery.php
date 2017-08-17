<?php namespace App\Espinoso\DeliveryServices;

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
     * @param array $params
     */
    public function sendMessage(array $params = []): void
    {
        $this->telegram->sendMessage($params);
    }
}
