<?php namespace App\Espinoso\Handlers;

use Illuminate\Support\Facades\Log;
use Telegram\Bot\Exceptions\TelegramResponseException;
use Telegram\Bot\Laravel\Facades\Telegram;

abstract class EspinosoHandler
{
    abstract public function handle($updates, $context = null);

    public function shouldHandle($updates, $context = null)
    {
        return $this->isTextMessage($updates);
    }

    protected function isTextMessage($updates)
    {
    	return isset($updates->message) && isset($updates->message->text); 
    }

    public function handleError(TelegramResponseException $e, $updates)
    {
        Log::info(json_encode($updates));
        Log::error($e);

        $chat = $updates->message->chat->type == 'group'
            ? "{$updates->message->chat->title}"
            : ($updates->message->chat->type == 'private'
                ? "{$updates->message->chat->first_name} (@{$updates->message->chat->username})"
                : "");
        $error = "Fuck! Something blow up
 - `{$e->getMessage()}`
 - *From:* {$updates->message->from->first_name} (@{$updates->message->from->username})
 - *Chat:* {$chat}
 - *Text:* _{$updates->message->text}_

View Log for details";

        Telegram::sendMessage([
            'chat_id' => config('espinoso.chat.dev'),
            'text'    => $error,
            'parse_mode' => 'Markdown',
        ]);
    }

    public function __toString()
    {
        return self::class;
    }


}