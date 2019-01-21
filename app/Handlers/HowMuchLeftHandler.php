<?php

namespace App\Handlers;

use Carbon\Carbon;

class HowMuchLeftHandler extends MultipleCommand
{

    protected $ignorePrefix = true;

    protected $signature   = "cuanto falta?";
    protected $description = "El sueño del Saba";

    protected $patterns = [
        [
            'name' => 'saba-day',
            'pattern' => "cuanto falta unq?"
        ],[
            'name' => 'macri-day',
            'pattern' => "chau macri?"
        ],[
            'name' => 'list-days',
            'pattern' => "cuanto falta?"
        ],
        ];

    protected function handleSabaDay(): void
    {
        $diff = trans(now()->diffInDays(Carbon::create(now()->year, 03, 18)));
        $this->espinoso->reply("{$diff} días");
    }

    public function handleMacriDay(): void
    {
        $diff = trans(now()->diffInDays(Carbon::create(now()->year, 12, 10)));
        $this->espinoso->reply("{$diff} días");
    }

    public function handleListDays(): void
    {
        $diffMacri = trans(now()->diffInDays(Carbon::create(now()->year, 12, 10)));
        $diffUnq = trans(now()->diffInDays(Carbon::create(now()->year, 03, 18)));
        $diffGot = trans(now()->diffInDays(Carbon::create(now()->year, 04, 14)));
        $diffEndGame = trans(now()->diffInDays(Carbon::create(now()->year, 04, 26)));
        $diffCapMarvel = trans(now()->diffInDays(Carbon::create(now()->year, 03, 8)));

        $list = [
            'Captain Marvel' => $diffCapMarvel,
            'UNQ' => $diffUnq,
            'GOT' => $diffGot,
            'Endgame' => $diffEndGame,
            'Chau Mau' => $diffMacri,
        ];

        $resultList =[];
        foreach ($list as $key => $value) {
            if ($value<=0) {
                $value = 'Llegoooooo';
            }
            array_push($resultList, "{$key}: {$value} días");
        }

        $resultList = collect($resultList)->map(function ($item) {
            return " {$item}";
        })->implode("\n");

        $this->espinoso->reply("{$resultList}");
    }
}
