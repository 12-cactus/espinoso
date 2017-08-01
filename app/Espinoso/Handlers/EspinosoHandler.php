<?php namespace App\Espinoso\Handlers;

use Exception;
use Illuminate\Support\Facades\Log;
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

    public function handleError(Exception $e, $updates)
    {
        Log::info(json_encode($updates));
        Log::error($e);

        $chat = $updates->message->chat->type == 'group'
            ? "{$updates->message->chat->title}"
            : ($updates->message->chat->type == 'private'
                ? "{$updates->message->chat->first_name} (@{$updates->message->chat->username})"
                : "");
        $username = isset($updates->message->from->username)
            ? " (@{$updates->message->from->username})"
            : '';
        $error = "Fuck! Something blow up on {$this}
 - `{$e->getMessage()}`
 - *From:* {$updates->message->from->first_name}{$username}
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