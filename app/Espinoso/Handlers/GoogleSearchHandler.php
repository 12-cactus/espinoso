<?php

namespace App\Espinoso\Handlers;

use stdClass;
use App\Facades\GoogleSearch;

class GoogleSearchHandler extends EspinosoCommandHandler
{
    protected $ignorePrefix = true;
    protected $pattern = "(?'i'\b(gg)\b)(?'query'.+)$";

    protected $signature   = "gg <cosa a buscar>";
    protected $description = "Googleanding... ";

    public function handle(): void
    {
        $info = GoogleSearch::getResults($this->matches['query']);

        if (empty($info)) {
            $this->replyNotFound();
            return;
        }

        $list = collect($info)->map(function (stdClass $node) {
            return " - *{$node->name}* -> {$node->url} -> {$node->snippet}";
        })->implode("\n");

        $this->espinoso->reply(trans('messages.search.google', compact('list')));
    }
}
