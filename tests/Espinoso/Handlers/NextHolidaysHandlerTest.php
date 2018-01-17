<?php namespace Tests\Espinoso\Handlers;

use App\Facades\GuzzleClient;
use Carbon\Carbon;
use Mockery;
use Symfony\Component\DomCrawler\Crawler;
use App\Espinoso\Handlers\NextHolidaysHandler;

class NextHolidaysHandlerTest extends HandlersTestCase
{
    /**
     * @test
     */
    public function it_should_handle_when_match_regex()
    {
        // Arrange
        $handler = $this->makeHandler();
        $updates = [
            $this->makeMessage(['text' => 'espi feriado']),
            $this->makeMessage(['text' => 'espi prox feriado']),
            $this->makeMessage(['text' => 'espi próximo feriado']),
            $this->makeMessage(['text' => 'espi proximo feriado']),
            $this->makeMessage(['text' => 'espi feriados']),
            $this->makeMessage(['text' => 'espi prox feriados']),
            $this->makeMessage(['text' => 'espi próximos feriados']),
            $this->makeMessage(['text' => 'espi proximos feriados']),
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
            $this->makeMessage(['text' => 'feriado']),
            $this->makeMessage(['text' => 'feriados']),
            $this->makeMessage(['text' => 'próximo feriado']),
            $this->makeMessage(['text' => 'proximo feriado']),
            $this->makeMessage(['text' => 'prox feriado']),
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
        $jsonText = '[{"motivo":"Año Nuevo","tipo":"inamovible","dia":1,"mes":1,"id":"año-nuevo"},{"motivo":"Carnaval","tipo":"inamovible","dia":12,"mes":2,"id":"carnaval"},{"motivo":"Carnaval","tipo":"inamovible","dia":13,"mes":2,"id":"carnaval"},{"motivo":"Día Nacional de la Memoria por la Verdad y la Justicia","tipo":"inamovible","dia":24,"mes":3,"id":"memoria-verdad-justicia"},{"motivo":"Día del Veterano y de los Caídos en la Guerra de Malvinas","tipo":"inamovible","dia":2,"mes":4,"id":"veteranos-malvinas"},{"motivo":"Día del Trabajador","tipo":"inamovible","dia":1,"mes":5,"id":"trabajador"},{"motivo":"Día de la Revolución de Mayo","tipo":"inamovible","dia":25,"mes":5,"id":"revolucion-mayo"},{"motivo":"Paso a la Inmortalidad del Gral. Don Martín Güemes","tipo":"nolaborable","dia":17,"mes":6,"id":"martin-guemes"},{"motivo":"Paso a la Inmortalidad del General Manuel Belgrano","tipo":"inamovible","dia":20,"mes":6,"id":"belgrano"},{"motivo":"Día de la Independencia","tipo":"inamovible","dia":9,"mes":7,"id":"independencia"},{"motivo":"Paso a la Inmortalidad del General José de San Martín","tipo":"trasladable","original":"17-08","dia":20,"mes":8,"id":"san-martin"},{"motivo":"Día del Respeto a la Diversidad Cultural","tipo":"trasladable","original":"12-10","dia":15,"mes":10,"id":"diversidad"},{"motivo":"Día de la Soberanía Nacional","tipo":"trasladable","original":"20-11","dia":19,"mes":11,"id":"soberania-nacional"},{"motivo":"Inmaculada Concepción de María","tipo":"inamovible","dia":8,"mes":12,"id":"inmaculada-maria"},{"motivo":"Feriado Puente Turístico","tipo":"puente","dia":24,"mes":12,"id":"puente-turistico"},{"motivo":"Navidad","tipo":"inamovible","dia":25,"mes":12,"id":"navidad"},{"motivo":"Feriado Puente Turístico","tipo":"puente","dia":31,"mes":12,"id":"puente-turistico"}]';

        Carbon::setTestNow(Carbon::create(2018, 1, 7));

        $crawler = Mockery::mock(Crawler::class);
        $crawler->shouldReceive('getBody')->andReturnSelf();
        $crawler->shouldReceive('getContents')->andReturn($jsonText);

        GuzzleClient::shouldReceive('request')
            ->withArgs(['GET', config('espinoso.url.holidays')])
            ->andReturn($crawler);

        $text = "Manga de vagos, *quedan 16 feriados* en todo el año.

 - *Carnaval*, inamovible, 12/2 (36)
 - *Carnaval*, inamovible, 13/2 (37)
 - *Día Nacional de la Memoria por la Verdad y la Justicia*, inamovible, 24/3 (76)
 - *Día del Veterano y de los Caídos en la Guerra de Malvinas*, inamovible, 2/4 (85)
 - *Día del Trabajador*, inamovible, 1/5 (114)
 - *Día de la Revolución de Mayo*, inamovible, 25/5 (138)
 - *Paso a la Inmortalidad del Gral. Don Martín Güemes*, nolaborable, 17/6 (161)
 - *Paso a la Inmortalidad del General Manuel Belgrano*, inamovible, 20/6 (164)
 - *Día de la Independencia*, inamovible, 9/7 (183)
 - *Paso a la Inmortalidad del General José de San Martín*, trasladable, 20/8 (225)
 - *Día del Respeto a la Diversidad Cultural*, trasladable, 15/10 (281)
 - *Día de la Soberanía Nacional*, trasladable, 19/11 (316)
 - *Inmaculada Concepción de María*, inamovible, 8/12 (335)
 - *Feriado Puente Turístico*, puente, 24/12 (351)
 - *Navidad*, inamovible, 25/12 (352)
 - *Feriado Puente Turístico*, puente, 31/12 (358)";

        $this->espinoso->shouldReceive('reply')->once()->with($text);

        $handler = $this->makeHandler();
        $update = $this->makeMessage(['text' => 'espi feriados']);

        // Act
        $handler->shouldHandle($update);
        $handler->handle($update);
        $this->assertTrue(true);
    }

    /**
     * @return NextHolidaysHandler
     */
    protected function makeHandler(): NextHolidaysHandler
    {
        return new NextHolidaysHandler($this->espinoso);
    }
}
