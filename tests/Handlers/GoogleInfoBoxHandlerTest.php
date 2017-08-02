<?php namespace Tests\Feature;

use Mockery;
use App\Facades\GoutteClient;
use Tests\Handlers\HandlersTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\Espinoso\Handlers\GoogleInfoBoxHandler;

class GoogleInfoBoxHandlerTest extends HandlersTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->handler = new GoogleInfoBoxHandler;
    }

    /**
     * @test
     */
    public function it_should_handle_when_match_regex()
    {
        $updates = [
            $this->update(['text' => 'espi info bla']),
            $this->update(['text' => 'espinoso info bla bla']),
            $this->update(['text' => 'info bla bla bla']),
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
            $this->update(['text' => 'espiinfo nup']),
            $this->update(['text' => 'espi infonup']),
            $this->update(['text' => 'espinosoinfo tampoco']),
            $this->update(['text' => 'espinoso infotampoco']),
            $this->update(['text' => 'gib nop']),
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
        // Mocking Action
        $query = 'got';
        $crawler = Mockery::mock(Crawler::class);
        $crawler->shouldReceive('filter')->andReturnSelf();
        $crawler->shouldReceive('each')->andReturn([]);
        GoutteClient::shouldReceive('request')
            ->withArgs(['GET', config('espinoso.url.info') . rawurlencode($query)])
            ->andReturn($crawler);

        $message = [
            'chat_id' => 123,
            'text'   => "",
            'parse_mode' => 'Markdown',
        ];
        Telegram::shouldReceive('sendMessage')->once()->with($message);

        // Real Action
        $update = $this->update([
            'chat' => ['id' => 123],
            'text' => 'espi info got'
        ]);

        $this->handler->shouldHandle($update);
        $this->handler->handle($update);
    }
}
