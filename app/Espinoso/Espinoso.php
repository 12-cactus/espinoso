<?php namespace App\Espinoso;

use Exception;
use Telegram\Bot\Objects\Message;
use Illuminate\Support\Collection;
use App\Espinoso\Handlers\EspinosoHandler;
use App\Espinoso\DeliveryServices\EspinosoDeliveryInterface;

/**
 * Class Espinoso
 * @package Espinoso
 */
class Espinoso
{
    /**
     * @var array
     */
    protected $handlers;
    /**
     * @var EspinosoDeliveryInterface
     */
    protected $delivery;
    /**
     * @var Message
     */
    protected $message;

    public function __construct(Collection $handlers)
    {
        $this->handlers = $handlers;
    }

    /**
     * @param Message $message
     */
    public function executeHandlers(Message $message)
    {
        $this->message = $message;
        $this->getHandlers()->map(function ($handler) {
            return new $handler($this);
        })->filter(function (EspinosoHandler $handler) use ($message) {
            return $handler->shouldHandle($message);
        })->each(function (EspinosoHandler $handler) use ($message) {
            try {
                $handler->handle($message);
            } catch (Exception $e) {
                $handler->handleError($e, $message);
            }
        });
    }

    /**
     * @param int $chatId
     * @param string $text
     * @param string $format
     * @param array $options
     */
    public function sendMessage(int $chatId, string $text, string $format = 'Markdown', array $options = []): void
    {
        $params = array_merge($options, [
            'chat_id' => $chatId,
            'text'    => $text,
            'parse_mode' => $format
        ]);

        $this->delivery->sendMessage($params);
    }

    /**
     * @param string $text
     * @param string $format
     * @param array $options
     */
    public function reply(string $text, string $format = 'Markdown', array $options = []): void
    {
        $this->sendMessage($this->message->getChat()->getId(), $text, $format, $options);
    }

    /**
     * @param string $url
     * @param string $caption
     * @param array $options
     */
    public function replyImage(string $url, string $caption = '', array $options = []): void
    {
        $params = array_merge($options, [
            'chat_id' => $this->message->getChat()->getId(),
            'photo'   => $url,
            'caption' => $caption
        ]);

        $this->delivery->sendImage($params);
    }

    /**
     * @param string $sticker
     * @param array $options
     */
    public function replySticker(string $sticker, array $options = []): void
    {
        $params = array_merge($options, [
            'chat_id' => $this->message->getChat()->getId(),
            'sticker' => $sticker,
        ]);

        $this->delivery->sendSticker($params);
    }

    /**
     * @param EspinosoDeliveryInterface $delivery
     */
    public function setDelivery(EspinosoDeliveryInterface $delivery)
    {
        $this->delivery = $delivery;
    }

    /**
     * @return Collection
     */
    public function getHandlers(): Collection
    {
        return $this->handlers;
    }

//    public function register(stdClass $update)
//    {
//        $from = $update->message->from;
//
//        $user = TelegramUser::whereTelegramId($from->id)->first();
//        if (!$user) {
//            $user = new TelegramUser;
//            $user->telegram_id = $from->id;
//        }
//
//        $user->first_name  = $from->first_name ?? '';
//        $user->last_name   = $from->last_name ?? '';
//        $user->username   = $from->username ?? '';
//        $user->save();
//    }

}
