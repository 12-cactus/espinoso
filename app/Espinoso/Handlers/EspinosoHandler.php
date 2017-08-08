<?php namespace App\Espinoso\Handlers;

use Exception;
use Telegram\Bot\Objects\Message;
use Telegram\Bot\Api as ApiTelegram;
use Illuminate\Support\Facades\Log;

abstract class EspinosoHandler
{
    /**
     * @var ApiTelegram
     */
    protected $telegram;

    public function __construct(ApiTelegram $telegram)
    {
        $this->telegram = $telegram;
    }

    abstract public function shouldHandle(Message $message): bool;

    abstract public function handle(Message $message);

    public function handleError(Exception $e, Message $message)
    {
        $clazz = get_called_class();
        Log::error($clazz);
        Log::error($message);
        Log::error($e);

        $chat = $message->getChat();
        $username = $chat->getUsername() ? " (@{$chat->getUsername()})" : "";
        $fromUser = $chat->getFirstName() . $username;

        // chat could be private, group, supergroup or channel
        $fromChat = $chat->getType() == 'private' ? $fromUser : $chat->getTitle();

        $error = "Fuck! Something blow up on {$clazz}
 - `{$e->getMessage()}`
 - *From:* {$fromUser}
 - *Chat:* {$fromChat}
 - *Text:* _{$message->getText()}_

View Log for details";

        $this->telegram->sendMessage([
            'chat_id' => config('espinoso.chat.dev'),
            'text'    => $error,
            'parse_mode' => 'Markdown',
        ]);
    }

    public function __toString()
    {
        return get_called_class();
    }


}