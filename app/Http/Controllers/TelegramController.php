<?php
namespace App\Http\Controllers;

use App\Espinoso\Espinoso;
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
        $client = new \GuzzleHttp\Client();
        $response = $client->get('https://api.github.com/repos/12-cactus/espinoso/events')->getBody()->getContents();
        $response = json_decode($response);
        $name = explode(' ', $response[0]->payload->commits[0]->author->name);
        $commit = $response[0]->payload->commits[0]->message;

        Telegram::sendMessage([
            'chat_id' => env('TELEGRAM_DEVS_CHANNEL'),
            'text' => "De nuevo el pelotudo de $name[0] comiteando giladas, mirá lo que hizo esta vez: $commit"
        ]);
    }
}