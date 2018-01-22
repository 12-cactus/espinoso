<?php

namespace App\Handlers;

use stdClass;
use Carbon\Carbon;
use App\Facades\GuzzleClient;

class NextHolidaysHandler extends BaseCommand
{
    /**
     * @var string
     */
    protected $pattern = "(\b(pr(o|ó)x(imo(s?))?)\b\s+)?(\b(feriado(s?))\b)(?'size'.+)?$";

    protected $signature = "espi feriados [cantidad]";
    protected $description = "feriados para rascarse la pelusa";


    public function handle(): void
    {
        $crawler = GuzzleClient::request('GET', config('espinoso.url.holidays').now()->year.'?incluir=opcional')->getBody()->getContents();
        $holidays = collect(json_decode($crawler));

        //filtro los feriados ya pasados
        $filteredList = $holidays->filter(function ($holiday) {
            return (Carbon::create(now()->year, $holiday->mes, $holiday->dia)->isFuture());
        });

        //filtro quedando solo los feriados cristianos y los no opcionales
        $rejectList =  $filteredList->filter(function ($holiday) {
            return (property_exists($holiday, 'opcional') and
                property_exists($holiday, 'religion') and
                $holiday->religion == 'cristianismo') or
                ! property_exists($holiday, 'opcional');
        });

        $count = $rejectList->count();

        if(!empty($this->matches['size']))
            $rejectList = $rejectList->take($this->matches['size']);

        $list = $rejectList->map(function (stdClass $holiday) {
            return $this->parseHoliday($holiday);
        })->implode("\n");

        $this->espinoso->reply(trans('messages.feriados', compact('count', 'list')));
    }

    protected function parseHoliday(stdClass $holiday)
    {
        $diff = now()->diffInDays(Carbon::create(now()->year, $holiday->mes, $holiday->dia));

        return " - *{$holiday->motivo}*, {$holiday->tipo}, {$holiday->dia}/{$holiday->mes} ({$diff})";
    }
}
