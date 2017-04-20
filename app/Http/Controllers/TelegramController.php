<?php
namespace App\Http\Controllers;

use App\Espinoso\Espinoso;
use App\Espinoso\Helpers\Msg;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramController extends Controller
{
    public function handleUpdates()
    {
        try {
            $updates = Telegram::getWebhookUpdates();
            $updates = json_decode($updates);

            Espinoso::handleTelegramUpdates($updates);

        } catch (\Exception $e) {
            Log::error(json_encode($updates));
            Log::error($e);
        }
    }

    public function setWebhook()
    {
        return Telegram::setWebhook(['url' => 'https://espinoso.herokuapp.com/handle-update']);
    }

    public function githubWebhook()
    {
        $client = new Client;
        $response = $client->get('https://api.github.com/repos/12-cactus/espinoso/events')->getBody()->getContents();
        $response = json_decode($response);
        $commit = $response[0]->payload->commits[0];
        $author = explode(' ', $commit->author->name)[0];

        $message = <<<MD
De nuevo el pelotudo de `$author` commiteando giladas, mirá lo que hizo esta vez:
_{$commit->message}_
{$commit->url}
MD;

        Telegram::sendMessage([
            'chat_id' => env('TELEGRAM_DEVS_CHANNEL'),
            'text' => Msg::md($message),
        ]);
    }
}