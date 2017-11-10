<?php namespace App\Espinoso\Handlers;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use App\Facades\WeatherSearch;
use Cmfcmf\OpenWeatherMap\Forecast;
use App\Espinoso\CronicaGenerator;

class WeatherHandler extends EspinosoCommandHandler
{
    protected $ignorePrefix = true;
    protected $pattern = "(\b(clima)\b)\s+(?'p'(este|el)\s+)?(?'day'(lunes|martes|mi(e|é)rcoles|jueves|viernes|s(a|á)bado|domingo))$";

    protected $signature   = "[espi] clima este lunez|martes|...";
    protected $description = "odio esta mierda...";


    public function handle(): void
    {
        $date = $this->getNearestDateFromDay($this->getDay());

        $forecasts = $this->getWeatherForcastsForDate($date);
        if ($forecasts->isEmpty()) {
            $this->replyNotFound();
        }

        $cronicaTitleUrl = $this->getCronicaTitleUrlForForecast($forecasts->first());
        if (!empty($cronicaTitleUrl))
            $this->espinoso->reply($cronicaTitleUrl);

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
        if (!array_key_exists($code, $titles))
            $code = intdiv($code, 100);

        if (!array_key_exists($code, $titles))
            return "";

        return $titles[$code];
    }

    /**
     * @return array
     */
    protected function cronicalTitlesMapping(): array
    {
        $titles = [
            2 => "Cuidado con el rayo que te parte al medio!",
            3 => "Atento el que quiere ver gotas!",
            5 => "Lindo dia para ponerla!",
            8 => "",
            800 => "Se vienen las tanguitas!",
            801 => "", // few clouds
            802 => "", // scattered clouds
            803 => "", // broken clouds
            804 => "", // overcast clouds
            9 => "Guarda que se te vuela la peluca!",
            951 => "", // calm
            952 => "", // light breeze
            953 => "", // gentle breeze
            954 => "", // moderate breeze
            955 => "", // fresh breeze
            956 => "", // strong breeze
            957 => "", // high wind, near gale
            958 => "", // gale
            959 => "", // severe gale
            960 => "", // storm
            961 => "", // violent storm
            962 => "", // hurricane
        ];
        return $titles;
    }
}
