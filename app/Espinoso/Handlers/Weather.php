<?php
namespace App\Espinoso\Handlers ; 

use App\Espinoso\Helpers\Msg;
use Cmfcmf\OpenWeatherMap\Forecast;
use Gmopx\LaravelOWM\LaravelOWM;
use Mockery\CountValidator\Exception;
use Telegram\Bot\Laravel\Facades\Telegram;

class Weather extends EspinosoHandler
{
    public function shouldHandle($updates, $context=null) 
    {
        if ( ! $this->isTextMessage($updates) ) return false ; 

        return preg_match($this->regex(), $updates->message->text);
    }

    public function handle($updates, $context=null)
    {
        $day = $this->extractDay($updates->message->text);
        $dayEn = $this->translateDay($day);
        $date = $this->getNearestDateFromDay($dayEn);

        $response = $this->buildResponse($date);

        Telegram::sendMessage(Msg::html($response)->build($updates));
    }

    private function buildMessage($response, $pattern, $updates)
    {
        if ($response instanceof Msg)
            return $response->build($updates, $pattern);
        else 
            return Msg::plain($response)->build($updates, $pattern);
    }
 
    private function regex()
    {
        return "/clima[^a-z0-9]+(?:este|el)[^a-z0-9].*(?'dia'lunes|martes|miercoles|jueves|viernes|sabado|domingo).*\??/i";
    }

    /**
     * @param $updates
     * @return string
     */
    public function buildResponse(\DateTime $date)
    {
        try {
            $weather = $this->getWeatherDescriptionForDate($date);
            if (empty($weather))
                throw new \Exception() ;
            $response = "está pronosticado " . $weather;
        } catch (Exception $e) {
            $response = "que se yo, forro";
        }

        return $response;
    }

    private function extractDay($text)
    {
        preg_match($this->regex(), $text, $matches);
        return $matches['dia'];
    }

    private function translateDay($day)
    {
        $days = [
            'lunes'     => 'Monday',
            'martes'    => 'Tuesday',
            'miercoles' => 'Wednesday',
            'jueves'    => 'Thursday',
            'viernes'   => 'Friday',
            'sabado'    => 'Saturday' ,
            'domingo'   => 'Sunday'
        ];
        return $days[$day];
    }

    private function getNearestDateFromDay($day)
    {
        $time = strtotime("next $day");
        return \DateTime::createFromFormat('U', $time);
    }

    private function getWeatherDescriptionForDate(\DateTime $date)
    {
        $owm = new LaravelOWM();

        $forecasts = $owm->getWeatherForecast('Buenos Aires', "es", "metric", 8, '');

        return collect($forecasts)
            ->filter(   function (Forecast $forecast) use ($date) { return $this->isForecastForDate($date, $forecast); } )
            ->map(      function (Forecast $forecast) { return $this->forecastToDescription($forecast); } )
            ->reduce(   function ($carray, $str) { return empty($carray) ? $str : $carray . "," . $str;  } , "")
        ;
    }

    private function isForecastForDate(\DateTime $date, Forecast $forecast)
    {
        return $forecast->time->day->format('Y-m-d') == $date->format('Y-m-d');
    }

    /**
     * @param $forecast
     * @return string
     */
    private function forecastToDescription(Forecast $forecast)
    {
        $from = $forecast->time->from->format('H:i');
        $to = $forecast->time->to->format('H:i');
        $minTemperature = $this->minTemperature($forecast);
        $maxTemperature = $this->maxTemperature($forecast);
        $description =  $forecast->weather->description;

        return "de " . $from . " a " . $to . " " . $description . " con temperaturas entre " . $minTemperature . " y " . $maxTemperature . " grados ";
    }

    /**
     * @param Forecast $forecast
     * @return string
     */
    private function minTemperature(Forecast $forecast)
    {
        return $forecast->temperature->min->getValue();
    }

    /**
     * @param Forecast $forecast
     * @return string
     */
    private function maxTemperature(Forecast $forecast)
    {
        return $forecast->temperature->max->getValue();
    }
}


