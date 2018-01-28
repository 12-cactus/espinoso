<?php

namespace Tests\Handlers;

use Mockery;
use App\Facades\GuzzleClient;
use App\Handlers\GitHubHandler;
use Psr\Http\Message\ResponseInterface;

class GitHubHandlerTest extends HandlersTestCase
{
    /**
     * @test
     */
    public function it_should_handle_when_receives_issue_command()
    {
        // Arrange
        $this->handler = $this->makeHandler();

        // Act & Assert
        $this->assertShouldHandle('espi issues');
        $this->assertShouldHandle('espi ver issues');
        $this->assertShouldHandle('espi show issues');
        $this->assertShouldHandle('espi list issues');
        $this->assertShouldHandle('espi listar issues');
        $this->assertShouldHandle('espi issue bla bla test');
        $this->assertShouldHandle("espi issue titulo\ndescription");
        $this->assertShouldHandle("espi issue title\nmulti\nline");
    }

    /**
     * @test
     */
    public function it_should_not_handle_when_receives_another_text()
    {
        // Arrange
        $this->handler = $this->makeHandler();

        // Act & Assert
        $this->assertShouldNotHandle('not espi issue bla bla test');
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
                config('github.issues-api'),
                [
                    'headers' => [
                        'Authorization' => "token ".config('github.token'),
                    ],
                    'json' => [
                        'title' => 'test facade',
                        'body' => ''
                    ]
                ]
            ])
            ->andReturn($response);

        // Arrange
        $this->espinoso
            ->shouldReceive('reply')
            ->once()
            ->with('[Issue creado!](http://url.facades.org/issues/12)');
        $handler = $this->makeHandler();
        $update  = $this->makeMessage(['text' => 'espi issue test facade']);

        // Act
        $handler->shouldHandle($update);
        $handler->handle($update);
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function it_handle_and_list_issues()
    {
        $jsonText = '[{
            "html_url": "https://github.com/12-cactus/espinoso/issues/103",
            "number": 103,
            "title": "Hacer Handler con el GSM"
        }]';

        $response = Mockery::mock(ResponseInterface::class);
        $response->shouldReceive('getStatusCode')->andReturn(200);
        $response->shouldReceive('getBody')->andReturn($jsonText);

        GuzzleClient::shouldReceive('request')
            ->withArgs(['GET', config('github.issues-api')])
            ->andReturn($response);

        $repo = config('github.issues');
        $issues = "[#103](https://github.com/12-cactus/espinoso/issues/103) Hacer Handler con el GSM";
        $text = trans('messages.issues.all', compact('repo', 'issues'));

        $this->espinoso->shouldReceive('reply')->once()->with($text);

        $handler = $this->makeHandler();
        $update = $this->makeMessage(['text' => 'espi issues']);

        // Act
        $handler->shouldHandle($update);
        $handler->handle($update);
        $this->assertTrue(true);
    }

    /**
     * @return GitHubHandler
     */
    protected function makeHandler(): GitHubHandler
    {
        return new GitHubHandler($this->espinoso);
    }
}
