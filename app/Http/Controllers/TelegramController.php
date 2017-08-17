<?php namespace App\Http\Controllers;

use GuzzleHttp\Client;
use App\Espinoso\Espinoso;
use Telegram\Bot\Objects\Message;
use Telegram\Bot\Api as ApiTelegram;
use Telegram\Bot\TelegramResponse;

class TelegramController extends Controller
{
    /**
     * Handle Telegram incoming message.
     *
     * @param ApiTelegram $telegram
     * @param Espinoso $espinoso
     */
    public function handleUpdates(ApiTelegram $telegram, Espinoso $espinoso)
    {
        $message = $telegram->getWebhookUpdates()->getMessage();

        if ($this->isNotTextMessage($message)) {
            return;
        }

        $command = $this->parseCommand($message->getText());

        if (!empty($command)) {
            $message['text'] = $this->parseCommandAsKeyword($command, $message);
        }

        $espinoso->executeHandlers($telegram, $message);

        return;
    }

    /**
     * Used for associate handleUpdates path as Telegram Webhook.
     * By default Telegram use its own hook to catch updates.
     *
     * @param ApiTelegram $telegram
     * @return TelegramResponse
     */
    public function setWebhook(ApiTelegram $telegram)
    {
        return $telegram->setWebhook(['url' => secure_url('handle-update')]);
    }

    /**
     * This method is a hook for github to catch & handle last commit.
     *
     * @param ApiTelegram $telegram
     */
    public function githubWebhook(ApiTelegram $telegram)
    {
        // FIXME get & send branch of commit
        $client = new Client;
        $response = $client->get('https://api.github.com/repos/12-cactus/espinoso/events')->getBody()->getContents();
        $response = json_decode($response);
        $commit = $response[0]->payload->commits[0];
        $link = "https://github.com/12-cactus/espinoso/commit/{$commit->sha}";
        $nombre = explode(' ', $commit->author->name)[0];

        $message = "De nuevo el pelotudo de `$nombre` commiteando giladas, mirá lo que hizo esta vez:_{$commit->message}_
[View Commit]({$link})";

        $telegram->sendMessage([
            'chat_id' => config('espinoso.chat.dev'),
            'text'    => $message,
            'parse_mode' => 'Markdown',
        ]);
    }

    /*
     * Internals
     */

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
     * @param string $text
     * @return string
     */
    protected function parseCommand(string $text)
    {
        preg_match('/^\/([^\s@]+)@?(\S+)?\s?(.*)$/', $text, $matches);

        return isset($matches[1]) ? trim($matches[1]) : '';
    }

    /**
     * @param string $command
     * @param Message $message
     * @return string
     */
    protected function parseCommandAsKeyword(string $command, Message $message): string
    {
        return str_replace("/{$command}", "espi {$command}", $message->getText());
    }
}
