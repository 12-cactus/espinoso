<?php namespace App\Espinoso\Handlers;

use App\Facades\GuzzleClient;
use stdClass;
use Carbon\Carbon;


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
        $crawler = GuzzleClient::request('GET', config('espinoso.url.holidays'))->getBody()->getContents();

        $holidays = collect(json_decode($crawler));

        $filteredList = $holidays->filter(
            function ($holiday){
                return Carbon::createFromDate(Carbon::today()->year, (int) $holiday->mes, (int) $holiday->dia)->isFuture();
            }
        );

        $count = $filteredList->count();

        $list = $filteredList->map(
            function (stdClass $holiday) {

                $diff = Carbon::today()->diffInDays(Carbon::createFromDate(Carbon::today()->year, (int) $holiday->mes, (int) $holiday->dia));

                return " - *{$holiday->motivo}*, {$holiday->tipo} , {$holiday->dia}/{$holiday->mes} ({$diff})";
            })->implode("\n");

        $text = "Manga de vagos, *quedan {$count} feriados* en todo el año.\n\n{$list}";

        $this->espinoso->reply($text);
    }

}

