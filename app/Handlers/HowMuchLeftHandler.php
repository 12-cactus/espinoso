<?php

namespace App\Handlers;

use Carbon\Carbon;
use Spatie\Emoji\Emoji;

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
        $this->espinoso->reply("Chupala Sabakuskas");
    }

    public function handleMacriDay(): void
    {
        $this->espinoso->reply("{$this->daysTo(2019, 12, 10)} días");
    }

    public function handleListDays(): void
    {
        $list = collect([
            'Captain Marvel' => $this->daysTo(2019, 3, 8),
            'UNQ' => $this->daysTo(2019, 3, 18),
            'GOT' => $this->daysTo(2019, 4, 14),
            'Endgame' => $this->daysTo(2019, 4, 26),
            'Chau Mau' => $this->daysTo(2019, 12, 10),
            'Days Gone' => $this->daysTo(2019, 4, 26)
        ]);

        $parsedList = $list->map(function ($days, $key) {
            $value = "{$days} días";
            if ($days <= 0) {
                $value = 'Ya pasó amigue!';
            }
            return "- {$key}: {$value}";
        })->implode("\n");

        $this->espinoso->reply($parsedList);
    }

    /**
     * @param $day
     * @param $month
     * @param $year
     * @return string
     */
    protected function daysTo($year, $month, $day)
    {
        $date = Carbon::create($year, $month, $day);
        return $date < now() ? -1 : now()->diffInDays($date);
    }
}
