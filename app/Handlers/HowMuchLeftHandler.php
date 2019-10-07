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
            'name' => 'got-day',
            'pattern' => "cuanto falta got?"
        ],[
            'name' => 'list-days',
            'pattern' => "cuanto falta?"
        ],
        ];

    public function handleSabaDay(): void
    {
        $this->espinoso->reply("Chupala Sabakuskas");
    }

    public function handleMacriDay(): void
    {
        $this->espinoso->reply("{$this->daysTo(2019, 12, 10)} días");
    }
    
    public function handleGotDay(): void
    {
        $days = $this->daysTo(2019, 4, 14);
        $message = $days > 0 ? "Winter Is Coming!! - {$days} días!!" : "Winter is Here!!!!";
        $this->espinoso->reply($message);
    }

    public function handleListDays(): void
    {
        $list = collect([
            'Chau Mau' => $this->daysTo(2019, 12, 10),
            'The Last Of Us II' => $this->daysTo(2020, 2, 21),
            'Rick and Morty' => $this->daysTo(2019, 11, 10)
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
