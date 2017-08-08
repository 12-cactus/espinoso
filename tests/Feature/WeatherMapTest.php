<?php

namespace Tests\Feature;

use App\Espinoso\Handlers\Weather;
use Telegram\Bot\Api;
use Telegram\Bot\Objects\Update;
use Tests\TestCase;

class WeatherMap extends TestCase
{
    public function testExample()
    {
        $w = new Weather(resolve(Api::class), $this->makeMessage([]));

        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        foreach ($days as $day)
        {
            $date = \DateTime::createFromFormat("U", strtotime("next $day"));
            $response = $w->buildResponse($date);
            $this->assertStringEndsNotWith("está pronosticado ", $response);
        }
    }
}
