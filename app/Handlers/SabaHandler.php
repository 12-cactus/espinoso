<?php

namespace App\Handlers;


use Carbon\Carbon;

class SabaHandler extends MultipleCommand
{

    protected $ignorePrefix = true;

    protected $signature   = "cuanto falta?";
    protected $description = "El sueño del Saba";

    protected $patterns = [
        [
            'name' => 'saba-day',
            'pattern' => "cuanto falta?"
        ],[
            'name' => 'macri-day',
            'pattern' => "chau macri?"
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
}
