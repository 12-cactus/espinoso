<?php

namespace App\Handlers;

use App\Facades\GoutteClient;
use Spatie\Emoji\Emoji;
use Symfony\Component\DomCrawler\Crawler;

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

        $crawler = $crawler->filter('.info');
        $movies = $crawler->each(function ($node) {
            $title = $this->getTitle($node);
            //$overview = $this->getOverview($node);
            $url = config('espinoso.url.themoviedb');
            $urlNameMovie = strtolower($title);
            $urlNameMovie = str_replace (' ', '-', $urlNameMovie);
            return "[{$title}]({$url}{$this->getViewMore($node)}-{$urlNameMovie})";
          });

        $movies = collect($movies)->map(function ($movie) {
            return " - {$movie}";
        })->implode("\n");

        $emoji = EMOJI::cinema();
        $response =  trans('messages.cinema', compact('emoji', 'movies'));

        $this->espinoso->replyDisablingPagePreview($response);
    }

    /*
    private function getOverview(Crawler $node)
    {
        return $node->filter('.overview')->text();
    }
    */

    private function getViewMore(Crawler $node)
    {
        return $node->filter('.view_more')
            ->filter('a')->attr('href');
    }

    private function getTitle(Crawler $node)
    {
        return $node->filter('a')->attr('title');
    }
}
