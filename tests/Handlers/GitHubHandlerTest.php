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
    /**
     * @test
     */
    public function it_should_handle_when_receives_issue_command()
    {
        $handler = new GitHubHandler($this->telegram);

        $update = $this->makeMessage(['text' => 'espi issue blablatest']);

        $this->assertTrue($handler->shouldHandle($update));
    }

    /**
     * @test
     */
    public function it_should_not_handle_when_receives_another_text()
    {
        $handler = new GitHubHandler($this->telegram);

        $update = $this->makeMessage(['text' => 'not espi issue blablatest']);

        $this->assertFalse($handler->shouldHandle($update));
    }

    /**
     * @test
     */
    public function it_handle_and_create_issue()
    {
        // Mocking
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
        $this->telegram->shouldReceive('sendMessage')->once()->with($message);
        $handler = new GitHubHandler($this->telegram);

        // Act
        $update = $this->makeMessage([
            'chat' => ['id' => 12345678],
            'text'   => 'espi issue test facade',
        ]);

        $handler->shouldHandle($update);
        $handler->handle($update);
    }
}
