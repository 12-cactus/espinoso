<?php namespace Tests\Espinoso\Handlers;

use Mockery;
use App\Facades\GoutteClient;
use Symfony\Component\DomCrawler\Crawler;
use App\Espinoso\Handlers\GoogleInfoBoxHandler;

class GoogleInfoBoxHandlerTest extends HandlersTestCase
{
    /**
     * @test
     */
    public function it_should_handle_when_match_regex()
    {
        // Arrange
        $handler = $this->makeHandler();
        $updates = [
            $this->makeMessage(['text' => 'espi info bla']),
            $this->makeMessage(['text' => 'espinoso info bla bla']),
            $this->makeMessage(['text' => 'info bla bla bla']),
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
        $handler = $this->makeHandler();
        $updates = [
            $this->makeMessage(['text' => 'espiinfo nup']),
            $this->makeMessage(['text' => 'espi infonup']),
            $this->makeMessage(['text' => 'espinosoinfo tampoco']),
            $this->makeMessage(['text' => 'espinoso infotampoco']),
            $this->makeMessage(['text' => 'gib nop']),
        ];

        // Act && Assert
        collect($updates)->each(function ($update) use ($handler) {
            $this->assertFalse($handler->shouldHandle($update));
        });
    }

    /**
     * @test
     */
    public function it_handle_and_return_movies()
    {
        // Mocking
        $query = 'got';
        $crawler = Mockery::mock(Crawler::class);
        $crawler->shouldReceive('filter')->andReturnSelf();
        $crawler->shouldReceive('each')->andReturn([]);
        GoutteClient::shouldReceive('request')
            ->withArgs(['GET', config('espinoso.url.info') . rawurlencode($query)])
            ->andReturn($crawler);

        // Arrange
        $text = "Uhhh... no hay un carajo!!\nO buscaste como el orto o estoy haciendo cualquiera!";
        $this->espinoso->shouldReceive('reply')->once()->with($text);
        $handler = $this->makeHandler();
        $update = $this->makeMessage(['text' => 'espi info got']);

        // Act
        $handler->shouldHandle($update);
        $handler->handle($update);
        $this->assertTrue(true);
    }

    /**
     * @return GoogleInfoBoxHandler
     */
    protected function makeHandler(): GoogleInfoBoxHandler
    {
        return new GoogleInfoBoxHandler($this->espinoso);
    }
}
