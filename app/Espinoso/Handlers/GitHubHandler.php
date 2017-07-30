<?php namespace App\Espinoso\Handlers;

use App\Facades\GuzzleClient;
use Telegram\Bot\Laravel\Facades\Telegram;

class GitHubHandler extends EspinosoCommandHandler
{
    protected $matches = [];

    public function shouldHandle($updates, $context = null)
    {
        return parent::shouldHandle($updates, $context)
            && $this->matchCommand('(issue)(\s)+(.+)$', $updates, $this->matches);
    }

    public function handle($updates, $context = null)
    {
        $title = $this->matches[6];

        $response = GuzzleClient::post(config('espinoso.url.issues'), [
            'headers' => [
                'Authorization' => "token ".config('espinoso.github.token'),
            ],
            'json' => ['title' => $title]
        ]);

        if ($response->getStatusCode() == 201) {
            $data = json_decode($response->getBody());
            $message = "[Issue creado!]({$data->html_url})";
        } else {
            $message = "No pude crear el issue, status ".$response->getStatusCode()."\n";
            $message .= $response->getBody();
        }

        Telegram::sendMessage([
            'chat_id' => $updates->message->chat->id,
            'text'    => $message,
            'parse_mode' => 'Markdown',
        ]);
    }
}
