<?php

namespace App\Handlers;

use Illuminate\Support\Str;
use App\Facades\GoutteClient;
use Spatie\Emoji\Emoji;

class CinemaHandler extends BaseCommand
{
    /**
     * @var string
     */
    protected $pattern = ".{0,100}\b(cine)\b.{0,100}$";

    protected $signature   = "espi cine";
    protected $description = "te muestro que hay para ver en el cine y ponerla";

    public function handle(): void
    {
        $crawler = GoutteClient::request('GET', config('espinoso.url.cinema'));

        $crawler = $crawler->filter('.title > a');

        $movies = $crawler->each(function ($node){
            $movie = Str::ucfirst(Str::lower($node->text()));
            $url = config('espinoso.url.hoyts');

            return "[{$movie}]({$url}{$node->attr('href')})";

            });
        $movies = collect($movies)->map(function ($movie) {
            return " - {$movie}";
        })->implode("\n");

        $emoji = EMOJI::cinema();
        $response =  trans('messages.cinema', compact('emoji','movies'));

        $this->espinoso->reply($response);
    }
}
