<?php

namespace App\Handlers;

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
            'pattern' => "(issue)\s+(?'title'.+)(\s|\n)*(?'body'(.|\n)+)?"
        ],[
            'name' => 'issues-list',
            'pattern' => "((list|listar|show|ver)\s+)?(issues)\s*(?'query'.+)?$"
        ],
    ];

    protected $signature   = "espi issues [texto a buscar]\nespi issue <title> [\\n<content>]";
    protected $description = "lista los issues o crea uno nuevo";

    public function handleIssueCreation(): void
    {
        $response = GuzzleClient::post(config('github.issues-api'), [
            'headers' => ['Authorization' => "token ".config('github.token')],
            'json'    => [
                'title' => $this->matches['title'],
                'body'  => $this->matches['body'] ?? ''
            ]
        ]);

        $data = json_decode($response->getBody());
        $text = "[Issue creado!]({$data->html_url})";
        if ($response->getStatusCode() !== 201) {
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

        if (!empty($this->matches['query'])) {
            $items = $items->filter(function ($issue) {
                return strrpos($issue->title, $this->matches['query']);
            });
        }

        $issues = $items->map(function (stdClass $issue) {
            return "[#{$issue->number}]({$issue->html_url}) {$issue->title}";
        })->implode("\n");

        $message = $items->isEmpty()
            ? trans('messages.issues.empty', compact('repo'))
            : trans('messages.issues.all', compact('repo', 'issues'));

        $this->espinoso->replyDisablingPagePreview($message);
    }
}
