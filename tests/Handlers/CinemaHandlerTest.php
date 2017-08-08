<?php namespace Tests\Feature;

use Mockery;
use App\Facades\GoutteClient;
use App\Espinoso\Handlers\CinemaHandler;
use Tests\Handlers\HandlersTestCase;
use Symfony\Component\DomCrawler\Crawler;

class CinemaHandlerTest extends HandlersTestCase
{
    /**
     * @test
     */
    public function it_should_handle_when_match_regex()
    {
        // Arrange
        $handler = new CinemaHandler($this->telegram);
        $updates = [
            $this->makeMessage(['text' => 'espi cine']),
            $this->makeMessage(['text' => 'espinoso cine?']),
            $this->makeMessage(['text' => 'espi cine??']),
            $this->makeMessage(['text' => 'espi cine!']),
            $this->makeMessage(['text' => 'espi cine!!!']),
            $this->makeMessage(['text' => 'espi ¿cine?']),
            $this->makeMessage(['text' => 'espi que hay en el cine']),
            $this->makeMessage(['text' => 'espi que hay en el cine?']),
        ];

        // Act & Assert
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
        $handler = new CinemaHandler($this->telegram);
        $updates = [
            $this->makeMessage(['text' => 'cinema']),
            $this->makeMessage(['text' => 'ig lacosacine']),
            $this->makeMessage(['text' => 'vamos al cine?']),
            $this->makeMessage(['text' => 'vamos al cine espi']),
            $this->makeMessage(['text' => 'vamos al cine, espi']),
            $this->makeMessage(['text' => 'vamos al cine, espi?']),
        ];

        // Act & Assert
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
        $text = "¿La pensás poner?\n¡Mete Netflix pelotud@, es mas barato!\nPero igual podes ver todas estas:\n\n";
        $message = ['chat_id' => 123, 'text' => $text];
        $this->telegram->shouldReceive('sendMessage')->once()->with($message);

        $crawler = Mockery::mock(Crawler::class);
        $crawler->shouldReceive('filter')->andReturnSelf();
        $crawler->shouldReceive('each')->andReturn([]);
        GoutteClient::shouldReceive('request')
            ->withArgs(['GET', config('espinoso.url.cinema')])
            ->andReturn($crawler);

        // Act
        $handler = new CinemaHandler($this->telegram);
        $update = $this->makeMessage([
            'chat' => ['id' => 123],
            'text' => 'espi cine'
        ]);
        $handler->handle($update);
    }
}
