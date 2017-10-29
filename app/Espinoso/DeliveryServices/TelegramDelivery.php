<?php namespace App\Espinoso\DeliveryServices;

use App\Facades\GuzzleClient;
use Telegram\Bot\Objects\Voice;
use Telegram\Bot\Objects\Message;
use Telegram\Bot\Api as ApiTelegram;
use Psr\Http\Message\StreamInterface;

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

    public function getFileUrl(array $params = []): string
    {
        return $this->telegram->getFile($params)['file_path'];
    }

    public function getVoiceStream(Voice $voice): StreamInterface
    {
        $id = $voice->getFileId();
        $file = $this->getFileUrl(['file_id' => $id]);

        $response = GuzzleClient::get(
            config('espinoso.telegram.url.file')."{$file}",
            ['stream' => true]
        );

        return $response->getBody();
    }
}
