<?php namespace App\Espinoso\Handlers;

use stdClass;
use App\Facades\GoutteClient;

class NextHolidaysHandler extends EspinosoCommandHandler
{
    /**
     * @var string
     */
    protected $pattern = "(\b(pr(o|ó)x(imo(s?))?)\b\s+)?(\b(feriado(s?))\b)$";

    protected $signature = "espi feriados";
    protected $description = "feriados para rascarse la pelusa";


    public function handle(): void
    {
        $crawler = GoutteClient::request('GET', config('espinoso.url.holidays'));

        dump($crawler);

        $holidays = collect($crawler);

        $count = $holidays->count();
        dump($count);

        $list = $holidays->map(
            function (stdClass $holiday) {
                return " - *{$holiday->motivo}*, {$holiday->tipo} , {$holiday->dia}-{$holiday->mes} ";
            })->implode("\n");

        $text = "Manga de vagos, *quedan {$count} feriados* en todo el año.\n{$list}";

        $this->espinoso->reply($text);
    }

}

