<?php namespace Tests\Espinoso\Handlers;

use Mockery;
use App\Facades\GoutteClient;
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
        /*
        // Mocking
        $jsonText = "var json = '[{\"description\":\"Paso a la Inmortalidad del General Jos\u00e9 de San Mart\u00edn\",\"phrase\":\"Lunes 21 de Agosto\",\"count\":10},{\"description\":\"D\u00eda del Respeto a la Diversidad Cultural\",\"phrase\":\"Lunes 16 de Octubre\",\"count\":66},{\"description\":\"D\u00eda de la Soberan\u00eda Nacional\",\"phrase\":\"Lunes 20 de Noviembre\",\"count\":101},{\"description\":\"Inmaculada Concepci\u00f3n de Mar\u00eda\",\"phrase\":\"Viernes 08 de Diciembre\",\"count\":119},{\"description\":\"Navidad\",\"phrase\":\"Lunes 25 de Diciembre\",\"count\":136}]';
			var position = 0;";

        $crawler = Mockery::mock(Crawler::class);
        $crawler->shouldReceive('filter')->with('script')->andReturnSelf();
        $crawler->shouldReceive('eq')->with(2)->andReturnSelf();
        $crawler->shouldReceive('text')->andReturn($jsonText);
        GoutteClient::shouldReceive('request')
            ->withArgs(['GET', config('espinoso.url.holidays')])
            ->andReturn($crawler);

        $text = "Manga de vagos, *quedan 5 feriados* en todo el año.
 - *Lunes 21 de Agosto*, Paso a la Inmortalidad del General José de San Martín (10 días)
 - *Lunes 16 de Octubre*, Día del Respeto a la Diversidad Cultural (66 días)
 - *Lunes 20 de Noviembre*, Día de la Soberanía Nacional (101 días)
 - *Viernes 08 de Diciembre*, Inmaculada Concepción de María (119 días)
 - *Lunes 25 de Diciembre*, Navidad (136 días)";

        $this->espinoso->shouldReceive('reply')->once()->with($text);
*/
        $handler = $this->makeHandler();
        $update = $this->makeMessage(['text' => 'espi feriados']);

        // Act
        $handler->shouldHandle($update);
        $handler->handle($update);
    }

    /**
     * @return NextHolidaysHandler
     */
    protected function makeHandler(): NextHolidaysHandler
    {
        return new NextHolidaysHandler($this->espinoso);
    }
}
