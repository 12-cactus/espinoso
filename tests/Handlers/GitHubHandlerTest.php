<?php

namespace Tests\Feature;

use Mockery;
use App\Facades\GuzzleClient;
use App\Espinoso\Handlers\GitHubHandler;
use Tests\Handlers\HandlersTestCase;
use Psr\Http\Message\ResponseInterface;
use Telegram\Bot\Laravel\Facades\Telegram;

class GitHubHandlerTest extends HandlersTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->handler = new GitHubHandler;
    }

    /**
     * @test
     */
    public function it_should_handle_when_receives_issue_command()
    {
        $update = $this->update(['text' => 'espi issue blablatest']);

        $this->assertTrue($this->handler->shouldHandle($update));
    }

    /**
     * @test
     */
    public function it_should_not_handle_when_receives_another_text()
    {
        $update = $this->update(['text' => 'not espi issue blablatest']);

        $this->assertFalse($this->handler->shouldHandle($update));
    }

    /**
     * @test
     */
    public function it_handle_and_create_issue()
    {
        $response = Mockery::mock(ResponseInterface::class);
        $response->shouldReceive('getStatusCode')->andReturn(201);
        $response->shouldReceive('getBody')->andReturn('{"html_url": "http://url.facades.org/issues/12"}');
        GuzzleClient::shouldReceive('post')
            ->withArgs([
                config('espinoso.url.issues'),
                [
                    'headers' => [
                        'Authorization' => "token ".config('espinoso.github.token'),
                    ],
                    'json' => ['title' => 'test facade']
                ]
            ])
            ->andReturn($response);
        $message = [
            'chat_id' => 12345678,
            'text' => '[Issue creado!](http://url.facades.org/issues/12)',
            'parse_mode' => 'Markdown',
        ];
        Telegram::shouldReceive('sendMessage')->once()->with($message);

        // Act
        $update = $this->update([
            'chat' => ['id' => 12345678],
            'text'   => 'espi issue test facade',
        ]);

        $this->handler->shouldHandle($update);
        $this->handler->handle($update);
    }
}
