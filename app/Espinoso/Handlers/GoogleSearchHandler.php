<?php

namespace App\Espinoso\Handlers;

use App\Facades\GoogleSearch;
use stdClass;

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
            $this->replyError();
            return;
        }

        $search = collect($info);

        $list = $search->map(
            function (stdClass $node) {
                return " - *{$node->name}* -> {$node->url} -> {$node->snippet}";
            })->implode("\n");


        $response = "Google me tira esto, si no te sirve jodete!:\n{$list}";

        $this->espinoso->reply($response);

    }
}