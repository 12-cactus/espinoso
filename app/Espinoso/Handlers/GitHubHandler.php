<?php namespace App\Espinoso\Handlers;

use App\Facades\GuzzleClient;
use Telegram\Bot\Laravel\Facades\Telegram;

class GitHubHandler extends EspinosoCommandHandler
{
    /**
     * @var string
     */
    protected $pattern = "(issue)(\s+)(?'title'.+)$";
    protected $title;

    public function shouldHandle($updates, $context = null)
    {
        $match = $this->matchCommand($this->pattern, $updates, $matches);
        $this->title = $matches['title'] ?? '';

        return parent::shouldHandle($updates) && $match;
    }

    public function handle($updates, $context = null)
    {
        $response = GuzzleClient::post(config('espinoso.url.issues'), [
            'headers' => [
                'Authorization' => "token ".config('espinoso.github.token'),
            ],
            'json' => ['title' => $this->title]
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
