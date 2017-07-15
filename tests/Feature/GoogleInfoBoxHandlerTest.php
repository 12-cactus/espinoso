<?php

namespace Tests\Feature;

use App\Espinoso\Handlers\GoogleInfoBoxHandler;
use Tests\TestCase;

class GoogleInfoBoxHandlerTest extends TestCase
{
    public function testQueAlMenosTraeAlgo()
    {
        $gib = new GoogleInfoBoxHandler();
        $result = $gib->buildResponse("gib wonder woman movie 2017");

        $this->assertContains("Mujer Maravilla: Película de 2017", $result['message']);
    }
}
