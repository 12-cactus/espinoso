<?php

namespace App\Handlers;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use App\Facades\WeatherSearch;
use Cmfcmf\OpenWeatherMap\Forecast;
use App\Lib\CronicaGenerator;

class WeatherHandler extends BaseCommand
{
    protected $ignorePrefix = true;
    protected $pattern = "(\b(clima)\b)\s+(?'p'(este|el)\s+)?(?'day'(lunes|martes|mi(e|é)rcoles|jueves|viernes|s(a|á)bado|domingo))$";

    protected $signature   = "[espi] clima este lunes|martes|...";
    protected $description = "está medio choto esto...";


    public function handle(): void
    {
        $date = $this->getNearestDateFromDay($this->getDay());

        $forecasts = $this->getWeatherForcastsForDate($date);
        if ($forecasts->isEmpty()) {
            $this->replyNotFound();
        }

        $cronicaTitleUrl = $this->getCronicaTitleUrlForForecast($forecasts->first());
        if (!empty($cronicaTitleUrl)) {
            $this->espinoso->reply($cronicaTitleUrl);
        }

        $weather = $this->getForecastsDescriptions($forecasts);
        $this->espinoso->reply("está pronosticado " . $weather, 'HTML');
    }

    protected function getDay()
    {
        $days = collect([
            'lunes'     => 'Monday',
            'martes'    => 'Tuesday',
            'miercoles' => 'Wednesday',
            'jueves'    => 'Thursday',
            'viernes'   => 'Friday',
            'sabado'    => 'Saturday' ,
            'domingo'   => 'Sunday'
        ]);

        $day = strtolower(Str::ascii(trim($this->matches['day'])));

        return $days->has($day) ? $days->get($day) : '';
    }

    protected function getNearestDateFromDay($day): Carbon
    {
        return new Carbon("next $day");
    }

    protected function getForecastsDescriptions(Collection $forecasts): string
    {
        return $forecasts
            ->map(function (Forecast $forecast) {
                return $this->forecastToDescription($forecast);
            })->reduce(function ($carry, $str) {
                return empty($carry) ? $str : $carry . "," . $str;
            }, '');
    }

    protected function getWeatherForcastsForDate(Carbon $date) : Collection
    {
        $forecasts = WeatherSearch::getWeatherForecast('Quilmes, AR', "es", "metric", 10, '');

        return collect($forecasts)->filter(function (Forecast $forecast) use ($date) {
            return $this->isForecastForDate($date, $forecast);
        });
    }

    protected function isForecastForDate(Carbon $date, Forecast $forecast): bool
    {
        return $forecast->time->day->format('Y-m-d') == $date->format('Y-m-d');
    }

    protected function forecastToDescription(Forecast $forecast) : string
    {
        $from = $forecast->time->from->format('H:i');
        $to   = $forecast->time->to->format('H:i');
        $min  = $forecast->temperature->min->getValue();
        $max  = $forecast->temperature->max->getValue();
        $descrip =  $forecast->weather->description;

        return "de {$from} a {$to} {$descrip} con temperaturas entre {$min} y {$max} grados";
    }

    protected function getCronicaTitleUrlForForecast(Forecast $forecast)
    {
        $text = $this->getCronicaTitleForForecast($forecast);
        return CronicaGenerator::getTitleUrl($text);
    }

    protected function getCronicaTitleForForecast(Forecast $forecast)
    {
        $titles = $this->cronicalTitlesMapping();

        $code = (int)$forecast->weather->id;
        if (!array_key_exists($code, $titles)) {
            $code = intdiv($code, 100);
        }

        if (!array_key_exists($code, $titles)) {
            return "Dan arreglá este asco de código";
        }

        return $titles[$code];
    }

    /**
     * @return array
     */
    protected function cronicalTitlesMapping(): array
    {
        $default = "Dan arreglá este asco de código";
        return [
            2 => "Cuidado con el rayo que te parte al medio!",
            3 => "Atento el que quiere ver gotas!",
            5 => "Lindo dia para ponerla!",
            8 => $default,
            800 => "Se vienen las tanguitas!",
            801 => $default, // few clouds
            802 => $default, // scattered clouds
            803 => $default, // broken clouds
            804 => $default, // overcast clouds
            9 => "Guarda que se te vuela la peluca!",
            951 => $default, // calm
            952 => $default, // light breeze
            953 => $default, // gentle breeze
            954 => $default, // moderate breeze
            955 => $default, // fresh breeze
            956 => $default, // strong breeze
            957 => $default, // high wind, near gale
            958 => $default, // gale
            959 => $default, // severe gale
            960 => $default, // storm
            961 => $default, // violent storm
            962 => $default, // hurricane
        ];
    }
}
