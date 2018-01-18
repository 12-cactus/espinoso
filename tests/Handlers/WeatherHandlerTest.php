<?php

namespace Tests\Handlers;

use Mockery;
use DateTime;
use Carbon\Carbon;
use Cmfcmf\OpenWeatherMap\Forecast;
use Cmfcmf\OpenWeatherMap\Util\Time;
use Cmfcmf\OpenWeatherMap\Util\Unit;
use Cmfcmf\OpenWeatherMap\Util\Weather;
use Cmfcmf\OpenWeatherMap\CurrentWeather;
use App\Facades\WeatherSearch;
use App\Espinoso\Handlers\WeatherHandler;

class WeatherHandlerTest extends HandlersTestCase
{
    /**
     * @test
     */
    public function it_should_handle_when_match_regex()
    {
        // Arrange
        $handler = $this->makeHandler();
        $updates = [
            $this->makeMessage(['text' => 'clima lunes']),
            $this->makeMessage(['text' => 'clima el lunes']),
            $this->makeMessage(['text' => 'clima este lunes']),
            $this->makeMessage(['text' => 'espi clima lunes']),
            $this->makeMessage(['text' => 'espi clima el lunes']),
            $this->makeMessage(['text' => 'espi clima este lunes']),
            $this->makeMessage(['text' => 'clima martes']),
            $this->makeMessage(['text' => 'clima miércoles']),
            $this->makeMessage(['text' => 'clima miercoles']),
            $this->makeMessage(['text' => 'clima jueves']),
            $this->makeMessage(['text' => 'clima viernes']),
            $this->makeMessage(['text' => 'clima sábado']),
            $this->makeMessage(['text' => 'clima sabado']),
            $this->makeMessage(['text' => 'clima domingo']),
            $this->makeMessage(['text' => 'clima Domingo']),
            $this->makeMessage(['text' => 'clima DOMINGO']),
        ];

        // Act && Assert
        collect($updates)->each(function ($update) use ($handler) {
            $this->assertTrue($handler->shouldHandle($update));
        });
    }

    /**
     * @test
     */
    public function it_should_not_handle_when_receives_another_text()
    {
        // Arrange
        $handler = $handler = $this->makeHandler();
        $updates = [
            $this->makeMessage(['text' => 'climalunes']),
            $this->makeMessage(['text' => 'clima pŕoximo lunes']),
            $this->makeMessage(['text' => 'espiclima lunes']),
        ];

        // Act && Assert
        collect($updates)->each(function ($update) use ($handler) {
            $this->assertFalse($handler->shouldHandle($update));
        });
    }

    /**
     * @test
     */
    public function it_handle_and_return_info()
    {
        // Mocking
        $min = Mockery::mock(Unit::class);
        $max = Mockery::mock(Unit::class);
        $day = Mockery::mock(DateTime::class);
        $time = Mockery::mock(Time::class);
        $dayTo = Mockery::mock(DateTime::class);
        $dayFrom = Mockery::mock(DateTime::class);
        $weather = Mockery::mock(Weather::class);
        $forecast = Mockery::mock(Forecast::class);
        $temperature = Mockery::mock(CurrentWeather::class);

        $min->shouldReceive('getValue')->andReturn('10.76');
        $max->shouldReceive('getValue')->andReturn('16.69');
        $temperature->min = $min;
        $temperature->max = $max;
        $weather->description = 'cielo claro';
        $weather->id = 800;
        $nextDay = Carbon::createFromTimestamp(strtotime('next monday'));
        $day->shouldReceive('format')->with('Y-m-d')->andReturn($nextDay->format('Y-m-d'));
        $dayFrom->shouldReceive('format')->with('H:i')->andReturn('00:00');
        $dayTo->shouldReceive('format')->with('H:i')->andReturn('23:59');
        $time->day  = $day;
        $time->to   = $dayTo;
        $time->from = $dayFrom;
        $forecast->time = $time;
        $forecast->temperature = $temperature;
        $forecast->weather = $weather;

        WeatherSearch::shouldReceive('getWeatherForecast')
            ->withArgs(['Quilmes, AR', "es", "metric", 10, ''])
            ->andReturn([$forecast]);

        $text = 'está pronosticado de 00:00 a 23:59 cielo claro con temperaturas entre 10.76 y 16.69 grados';
        $this->espinoso->shouldReceive('reply')->twice(); //->with($text, 'HTML');

        $handler = $this->makeHandler();
        $update = $this->makeMessage(['text' => 'espi clima lunes']);

        // Act
        $handler->shouldHandle($update);
        $handler->handle($update);
        $this->assertTrue(true);
    }

    /**
     * @return WeatherHandler
     */
    protected function makeHandler(): WeatherHandler
    {
        return new WeatherHandler($this->espinoso);
    }
}
