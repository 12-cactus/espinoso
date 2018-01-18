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
    protected $pattern = "(\b(pr(o|ó)x(imo(s?))?)\b\s+)?(\b(feriado(s?))\b)$";

    protected $signature = "espi feriados";
    protected $description = "feriados para rascarse la pelusa";


    public function handle(): void
    {
        $crawler = GuzzleClient::request('GET', config('espinoso.url.holidays'))->getBody()->getContents();
        $holidays = collect(json_decode($crawler));

        $filteredList = $holidays->filter(function ($holiday) {
            return Carbon::create(now()->year, $holiday->mes, $holiday->dia)->isFuture();
        });

        $count = $filteredList->count();
        $list = $filteredList->map(function (stdClass $holiday) {
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
