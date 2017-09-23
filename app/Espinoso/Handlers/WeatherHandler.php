<?php namespace App\Espinoso\Handlers;

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Facades\WeatherSearch;
use Telegram\Bot\Objects\Message;
use Cmfcmf\OpenWeatherMap\Forecast;

class WeatherHandler extends EspinosoCommandHandler
{
    protected $ignorePrefix = true;
    protected $pattern = "(\b(clima)\b)\s+(?'p'(este|el)\s+)?(?'day'(lunes|martes|mi(e|é)rcoles|jueves|viernes|s(a|á)bado|domingo))$";

    protected $signature   = "[espi] clima este lunez|martes|...";
    protected $description = "odio esta mierda...";


    public function handle(Message $message): void
    {
        $date = $this->getNearestDateFromDay($this->getDay());

        $weather = $this->getWeatherDescriptionForDate($date);

        if (empty($weather)) {
            $this->replyNotFound();
        }

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
        $time = strtotime("next $day");

        return Carbon::createFromFormat('U', $time);
    }

    protected function getWeatherDescriptionForDate(Carbon $date): string
    {
        $forecasts = WeatherSearch::getWeatherForecast('Quilmes, AR', "es", "metric", 10, '');

        return collect($forecasts)->filter(function (Forecast $forecast) use ($date) {
            return $this->isForecastForDate($date, $forecast);
        })->map(function (Forecast $forecast) {
            return $this->forecastToDescription($forecast);
        })->reduce(function ($carry, $str) {
            return empty($carry) ? $str : $carry . "," . $str;
        }, '');
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
}
