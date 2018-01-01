<?php

namespace App\Espinoso\Handlers;

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
            $this->replyError();
            return;
        }

        $response = "Google me tira esto, si no te sirve jodete!:\n{$info}";

        $this->espinoso->reply($response);

    }
}