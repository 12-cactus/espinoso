<?php namespace App\Espinoso\Handlers;

use App\Facades\GuzzleClient;
use stdClass;


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
        $crawler = GuzzleClient::request('GET', 'https://nolaborables.com.ar/api/v2/feriados/2018')->getBody()->getContents();

        $holidays = collect(json_decode($crawler));

        $count = $holidays->count();

        $list = $holidays->map(
            function (stdClass $holiday) {
                return " - *{$holiday->motivo}*, {$holiday->tipo} , {$holiday->dia}-{$holiday->mes} ";
            })->implode("\n");

        $text = "Manga de vagos, *quedan {$count} feriados* en todo el año.\n{$list}";

        $this->espinoso->reply($text);
    }

}

