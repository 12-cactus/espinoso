<?php

namespace Tests\Feature;

use App\Espinoso\Handlers\GoogleInfoBoxHandler;
use Tests\TestCase;

class GoogleInfoBoxHandlerTest extends TestCase
{
    public function testQueAlMenosTraeAlgo()
    {
        $gib = new GoogleInfoBoxHandler();
        dump($gib->buildResponse("gib wonder woman movie 2017"));
    }
}
