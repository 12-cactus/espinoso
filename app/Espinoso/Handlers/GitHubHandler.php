<?php namespace App\Espinoso\Handlers;

use App\Facades\GuzzleClient;
use Telegram\Bot\Objects\Message;

class GitHubHandler extends EspinosoCommandHandler
{
    /**
     * @var string
     */
    protected $pattern = "(issue)(\s+)(?'title'.+)$";
    protected $title;

    public function shouldHandle(Message $message): bool
    {
        $matching = $this->matchCommand($this->pattern, $message, $matches);
        $this->title = $matches['title'] ?? '';

        return $matching;
    }

    public function handle(Message $message)
    {
        $response = GuzzleClient::post(config('espinoso.url.issues'), [
            'headers' => [
                'Authorization' => "token ".config('espinoso.github.token'),
            ],
            'json' => ['title' => $this->title]
        ]);

        if ($response->getStatusCode() == 201) {
            $data = json_decode($response->getBody());
            $text = "[Issue creado!]({$data->html_url})";
        } else {
            $text = "No pude crear el issue, status ".$response->getStatusCode()."\n";
            $text .= $response->getBody();
        }

        $this->telegram->sendMessage([
            'chat_id' => $message->getChat()->getId(),
            'text'    => $text,
            'parse_mode' => 'Markdown',
        ]);
    }
}
