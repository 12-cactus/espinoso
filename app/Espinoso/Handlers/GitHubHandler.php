<?php namespace App\Espinoso\Handlers;

use App\Facades\GuzzleClient;
use Telegram\Bot\Objects\Message;

class GitHubHandler extends EspinosoCommandHandler
{
    /**
     * @var string
     */
    protected $pattern = "(issue)(\s+)(?'title'.+)$";

    protected $signature   = "espi issue <title>";
    protected $description = "genera un issue en el repo";

    public function handle(Message $message): void
    {
        $response = GuzzleClient::post(config('espinoso.url.issues'), [
            'headers' => ['Authorization' => "token ".config('espinoso.token.github')],
            'json'    => ['title' => $this->matches['title']]
        ]);

        if ($response->getStatusCode() == 201) {
            $data = json_decode($response->getBody());
            $text = "[Issue creado!]({$data->html_url})";
        } else {
            $text = "No pude crear el issue, status ".$response->getStatusCode()."\n";
            $text .= $response->getBody();
        }

        $this->espinoso->reply($text);
    }
}
