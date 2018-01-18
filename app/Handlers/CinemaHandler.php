<?php

namespace App\Handlers;

use Illuminate\Support\Str;
use App\Facades\GoutteClient;

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

        $movies = [];
        $crawler->filter('.title > a')
            ->each(function ($node) use (&$movies) {
                $movies[] = Str::ucfirst(Str::lower($node->text()));
            });
        $movies = collect($movies)->map(function ($movie) {
            return " - {$movie}";
        })->implode("\n");

        $response = "¿La pensás poner?
¡Mete Netflix pelotud@, es mas barato!
Pero igual podes ver todas estas:\n
{$movies}";

        $this->espinoso->reply($response);
    }
}
