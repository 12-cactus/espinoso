<?php namespace App\Espinoso\Handlers;

use stdClass;
use App\Facades\GuzzleClient;

class GitHubHandler extends MultipleCommand
{
    /**
     * @var string
     */
    protected $patterns = [
        [
            'name' => 'issue-creation',
            'pattern' => "(issue)\s+(?'title'.+)$"
        ],[
            'name' => 'issues-list',
            'pattern' => "((list|listar|show|ver)\s+)?(issues)\s*$"
        ],
    ];

    protected $signature   = "espi issues\nespi issue <title>";
    protected $description = "lista los issues o crea uno nuevo";

    public function handleIssueCreation(): void
    {
        $response = GuzzleClient::post(config('github.issues-api'), [
            'headers' => ['Authorization' => "token ".config('github.token')],
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

    public function handleIssuesList(): void
    {
        $response = GuzzleClient::request('GET', config('github.issues-api'));

        if ($response->getStatusCode() !== 200) {
            $this->replyError();
            return;
        }

        $repo   = config('github.issues');
        $items  = collect(json_decode($response->getBody()));
        $issues = $items->map(function (stdClass $issue) {
            return "[#{$issue->number}]({$issue->html_url}) {$issue->title}";
        })->implode("\n");

        $this->espinoso->reply(trans('messages.issues.all', compact('repo', 'issues')));
    }
}
