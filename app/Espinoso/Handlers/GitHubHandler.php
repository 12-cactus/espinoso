<?php

namespace App\Espinoso\Handlers;

use GuzzleHttp\Client;
use App\Espinoso\Helpers\Msg;
use Telegram\Bot\Laravel\Facades\Telegram;

class GitHubHandler extends EspinosoHandler
{
    protected $matches = [];

    public function shouldHandle($updates, $context = null)
    {
        return $this->isTextMessage($updates)
            && preg_match('/^(espi(noso)*)(\s)(issue)(\s)(.+)$/i', $updates->message->text, $this->matches);
    }

    public function handle($updates, $context = null)
    {
        $title = $this->matches[6];
        $token = config('espinoso.github.token');

        $client = new Client;
        $response = $client->post('https://api.github.com/repos/12-cactus/espinoso/issues', [
            'headers' => [
                'Authorization' => "token {$token}",
            ],
            'json' => ['title' => $title]
        ]);

        if ($response->getStatusCode() == 201) {
            $data = json_decode($response->getBody());
            $message = "[Issue creado!]({$data->url})";
        } else {
            $message = "No pude crear el issue, status ".$response->getStatusCode()."\n";
            $message .= $response->getBody();
        }
        Telegram::sendMessage(Msg::md($message)->build($updates));
    }
}