<?php namespace Tests\Feature;


use Mockery;
use App\Facades\GoutteClient;
use App\Espinoso\Handlers\CinemaHandler;
use Tests\Handlers\HandlersTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Telegram\Bot\Laravel\Facades\Telegram;

class CinemaHandlerTest extends HandlersTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->handler = new CinemaHandler;
    }

    /**
     * @test
     */
    public function it_should_handle_when_match_regex()
    {
        $updates = [
            $this->update(['text' => 'espi cine']),
            $this->update(['text' => 'espinoso cine?']),
            $this->update(['text' => 'espi cine??']),
            $this->update(['text' => 'espi cine!']),
            $this->update(['text' => 'espi cine!!!']),
            $this->update(['text' => 'espi ¿cine?']),
            $this->update(['text' => 'espi que hay en el cine']),
            $this->update(['text' => 'espi que hay en el cine?']),
        ];

        collect($updates)->each(function ($update) {
            $this->assertTrue($this->handler->shouldHandle($update));
        });
    }

    /**
     * @test
     */
    public function it_should_not_handle_when_receives_another_text()
    {
        $updates = [
            $this->update(['text' => 'cinema']),
            $this->update(['text' => 'ig lacosacine']),
            $this->update(['text' => 'vamos al cine?']),
            $this->update(['text' => 'vamos al cine espi']),
            $this->update(['text' => 'vamos al cine, espi']),
            $this->update(['text' => 'vamos al cine, espi?']),
        ];

        collect($updates)->each(function ($update) {
            $this->assertFalse($this->handler->shouldHandle($update));
        });
    }

    /**
     * @test
     */
    public function it_handle_and_return_movies()
    {
        $crawler = Mockery::mock(Crawler::class);
        $crawler->shouldReceive('filter')->andReturnSelf();
        $crawler->shouldReceive('each')->andReturn([]);
        GoutteClient::shouldReceive('request')
            ->withArgs(['GET', config('espinoso.url.cinema')])
            ->andReturn($crawler);

        $text = "¿La pensás poner?
¡Mete Netflix pelotud@, es mas barato!
Pero igual podes ver todas estas:\n
";
        $message = [
            'chat_id' => 123,
            'text'   => $text,
        ];
        Telegram::shouldReceive('sendMessage')->once()->with($message);

        $update = $this->update([
            'chat' => ['id' => 123],
            'text' => 'espi cine'
        ]);

        $this->handler->handle($update);
    }
}
