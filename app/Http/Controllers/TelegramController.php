<?php namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Exceptions\TelegramResponseException;
use App\Espinoso\Handlers\EspinosoHandler;

class TelegramController extends Controller
{
    public function handleUpdates()
    {
        $updates = json_decode(Telegram::getWebhookUpdates());

        collect(config('espinoso.handlers'))->map(function ($handler) {
            return resolve($handler);
        })->filter(function (EspinosoHandler $handler) use ($updates) {
            return $handler->shouldHandle($updates);
        })->each(function (EspinosoHandler $handler) use ($updates) {
            // FIXME make try-catch an aspect
            try {
                $handler->handle($updates);
            } catch (TelegramResponseException $e) {
                $handler->handleError($e, $updates);
            }
        });
    }

    public function setWebhook()
    {
        return Telegram::setWebhook(['url' => secure_url('handle-update')]);
    }

    public function githubWebhook()
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

        Telegram::sendMessage([
            'chat_id' => env('TELEGRAM_DEVS_CHANNEL'),
            'text'    => $message,
            'parse_mode' => 'Markdown',
        ]);
    }
}