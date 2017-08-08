<?php namespace App\Espinoso;

use Exception;
use Illuminate\Support\Collection;
use Telegram\Bot\Objects\Message;
use Telegram\Bot\Api as ApiTelegram;
use App\Espinoso\Handlers\EspinosoHandler;

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

    public function __construct(Collection $handlers)
    {
        $this->handlers = $handlers;
    }

    /**
     * @param ApiTelegram $telegram
     * @param Message $message
     */
    public function executeHandlers(ApiTelegram $telegram, Message $message)
    {
        $this->handlers->map(function ($handler) use ($telegram) {
            return new $handler($telegram);
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
