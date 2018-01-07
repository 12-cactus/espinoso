<?php

namespace App\Espinoso\Handlers;

use App\Facades\GuzzleClient;
use stdClass;

class IssuesListHandler extends EspinosoCommandHandler
{

    protected $ignorePrefix = true;
    protected $pattern = "(\b(issues? list)\b)|(\b(list issues?)\b)$";

    protected $signature = "issues list";
    protected $description = "Todos los kilombos que tengo...";

    public function handle(): void
    {
        $crawler = GuzzleClient::request('GET', config('espinoso.url.issues'))->getBody()->getContents();
        $list = collect(json_decode($crawler));

        $issues = $list->map(function (stdClass $issue) {
            return " - {$issue->number} - {$issue->title}\n {$issue->html_url}\n";
        })->implode("\n");

        $this->espinoso->reply($issues);
    }
}
