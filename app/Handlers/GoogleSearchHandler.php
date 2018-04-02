<?php

namespace App\Handlers;

use App\Facades\GoutteClient;
use Illuminate\Support\Str;
use Spatie\Emoji\Emoji;

class GoogleSearchHandler extends BaseCommand
{
    protected $ignorePrefix = true;
    protected $pattern = "(?'i'\b(gg)\b)(?'query'.+)$";

    protected $signature   = "gg <cosa a buscar>";
    protected $description = "Googleanding... ";

    public function handle(): void
    {
        $query = rawurlencode(trim($this->matches['query']));

        $crawler = GoutteClient::request('GET', config('espinoso.url.info') . $query);

        $filter = $crawler->filter('.r');

        $filter = $filter->filter('a')->each(function ($node) {

            $text = Str::ucfirst(Str::lower($node->text()));

            $href = explode('&sa=U&v', $node->attr('href'));
            $link = substr($href[0], 7);

            return "[{$text}]({$link})";
        });

        $list = collect($filter)->map(function ($info) {
            return " - {$info}";
        })->implode("\n");

        $emoji = EMOJI::CHARACTER_WHITE_DOWN_POINTING_BACKHAND_INDEX;
        $response =  trans('messages.search.google', compact('emoji', 'list'));

        $this->espinoso->reply($response, 'Markdown',['disable_web_page_preview'=>True]);
    }
}
