<?php

namespace Tests\Commands;


use Carbon\Carbon;

class ScheduleCommandTest extends CommandTestCase
{
    /**
     * @test
     */
    public function testOnPipisBirthday()
    {
        $date = Carbon::create(2019, 1, 6);
        Carbon::setTestNow($date);

        $this->espinoso
            ->shouldReceive('sendToCactus')
            ->with('Feliz cumple Pipi!! Que coman rico asado con los de videojuegos!');
        $this->appBindEspinoso($this->espinoso);

        $this->artisan('espi:agenda')->assertExitCode(0);
    }

    /**
     * @test
     */
    public function testOnSabakuskasBirthday()
    {
        $date = Carbon::create(2021, 7, 16);
        Carbon::setTestNow($date);

        $this->espinoso
            ->shouldReceive('sendToCactus')
            ->with('Feliz cumple Sabakuskas!! A ver cuando hacemos un asado en la unqui');
        $this->appBindEspinoso($this->espinoso);

        $this->artisan('espi:agenda')->assertExitCode(0);
    }

    /**
     * @test
     */
    public function testWithNoEvents()
    {
        $date = Carbon::create(2021, 7, 17);
        Carbon::setTestNow($date);

        $this->espinoso->shouldNotReceive('sendToCactus');
        $this->appBindEspinoso($this->espinoso);

        $this->artisan('espi:agenda')->assertExitCode(0);
    }

    /**
     * @test
     */
    public function testOnProgrammerDay()
    {
        $date = Carbon::create(2019, 9, 13);
        Carbon::setTestNow($date);

        $this->espinoso
            ->shouldReceive('sendToCactus')
            ->with("Feliz día monos tecleadores!!");
        $this->appBindEspinoso($this->espinoso);

        $this->artisan('espi:agenda')->assertExitCode(0);
    }

    /**
     * @test
     */
    public function testOnProgrammerDayAndLeansBirthday()
    {
        $date = Carbon::create(2020, 9, 12);
        Carbon::setTestNow($date);

        $this->espinoso
            ->shouldReceive('sendToCactus')
            ->with('Feliz cumple Lea!! Sos el OPM más ortiva que conozco, amargo!');
        $this->espinoso
            ->shouldReceive('sendToCactus')
            ->with("Feliz día monos tecleadores!!");
        $this->appBindEspinoso($this->espinoso);

        $this->artisan('espi:agenda')->assertExitCode(0);
    }
}
