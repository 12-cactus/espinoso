<?php namespace App\Espinoso;

use Exception;
use Illuminate\Support\Collection;
use Telegram\Bot\Objects\Message;
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
            return new $handler($this, $this->delivery);
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

    public function reply(string $text, string $format = 'Markdown', array $options = []): void
    {
        $params = array_merge($options, [
            'chat_id' => $this->message->getChat()->getId(),
            'text'    => $text,
            'parse_mode' => $format
        ]);

        $this->delivery->sendMessage($params);
    }

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
