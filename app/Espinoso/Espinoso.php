<?php namespace App\Espinoso;

use Telegram\Bot\Objects\Message;
use Telegram\Bot\Api as ApiTelegram;
use App\Espinoso\Handlers\EspinosoHandler;

/**
 * Class Espinoso
 * @package Espinoso
 */
class Espinoso
{
    public function handleTelegramUpdate(ApiTelegram $telegram)
    {
        $message = $telegram->getWebhookUpdates()->getMessage();

        if ($this->isNotTextMessage($message)) {
            return;
        }

        $command = $this->parseTelegramCommand($telegram, $message);

        if ($this->isTextCommand($command)) {
            $message['text'] = $this->parseCommandAsKeyword($command, $message);
        }

        $this->executeHandlers($telegram, $message);
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

    /*
     * Internals
     */

    /**
     * @param ApiTelegram $telegram
     * @param Message $message
     */
    protected function executeHandlers(ApiTelegram $telegram, Message $message)
    {
        collect(config('espinoso.handlers'))->map(function ($handler) use ($telegram, $message) {
            return new $handler($telegram, $message);
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
     * @param mixed $message
     * @return bool
     */
    protected function isTextMessage($message): bool
    {
        return $message !== null && $message->has('text');
    }

    /**
     * @param mixed $message
     * @return bool
     */
    protected function isNotTextMessage($message): bool
    {
        return !$this->isTextMessage($message);
    }

    /**
     * @param string $command
     * @return bool
     */
    protected function isTextCommand(string $command): bool
    {
        return !empty($command);
    }

    /**
     * @param string $command
     * @param Message $message
     * @return string
     */
    protected function parseCommandAsKeyword(string $command, Message $message): string
    {
        return str_replace("/{$command}", "espi {$command}", $message['text']);
    }

}
