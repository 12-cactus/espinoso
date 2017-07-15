<?php

namespace Tests\Feature;

use App\Espinoso\Handlers\Weather;
use Tests\TestCase;

class WeatherMap extends TestCase
{
    public function testExample()
    {
        $w = new Weather();

        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        foreach ($days as $day)
        {
            $date = \DateTime::createFromFormat("U", strtotime("next $day"));
            $response = $w->buildResponse($date);
            $this->assertStringEndsNotWith("está pronosticado ", $response);
        }
    }
}
