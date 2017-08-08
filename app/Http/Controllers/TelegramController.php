<?php namespace App\Http\Controllers;

use GuzzleHttp\Client;
use App\Espinoso\Espinoso;
use Telegram\Bot\Api as ApiTelegram;

class TelegramController extends Controller
{
    public function handleUpdates(ApiTelegram $telegram, Espinoso $espi)
    {
        $espi->handleTelegramUpdate($telegram);
    }

    public function setWebhook(ApiTelegram $telegram)
    {
        return $telegram->setWebhook(['url' => secure_url('handle-update')]);
    }

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
}
