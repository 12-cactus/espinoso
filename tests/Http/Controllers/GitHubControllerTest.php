<?php

namespace Tests\Http\Controllers;

use Mockery;
use Tests\TestCase;
use App\Espinoso;
use App\Facades\GuzzleClient;
use Espinaland\Deliveries\TelegramDelivery;
use App\Http\Controllers\GitHubController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class GitHubControllerTest
 * @package Tests\Http\Controllers
 */
class GitHubControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     *
     * @return void
     */
    public function it_should_retrieve_last_events()
    {
        // Arrange
        $espinoso = Mockery::mock(Espinoso::class);
        $telegram = Mockery::mock(TelegramDelivery::class);
        $espinoso->shouldReceive('setDelivery')->with($telegram);
        $espinoso->shouldReceive('sendMessage');
        $content = '[{
            "id":"7120495287",
            "type":"PushEvent",
            "actor":{
                "id":3334920,
                "login":"leandrojdl",
                "display_login":"leandrojdl"
            },
            "repo":{
                "id":106958674,
                "name":"leandrojdl/pruebas-espi",
                "url":"https://api.github.com/repos/leandrojdl/pruebas-espi"
            },
            "payload":{
                "push_id":2265279650,
                "ref":"refs/heads/master",
                "commits":[{
                    "sha":"7660698e7a08d2d7931690ba1f89115501adca9b",
                    "message":"Update README.md"
                }]
            },
            "created_at":"2018-01-19T01:32:33Z"
        }]';
        $stream = Mockery::mock(StreamInterface::class);
        $stream->shouldReceive('getContents')->andReturn($content);
        $response = Mockery::mock(ResponseInterface::class);
        $response->shouldReceive('getBody')->andReturn($stream);
        GuzzleClient::shouldReceive('get')->with(config('github.events'), [
            'auth' => [config('github.username'), config('github.token')]
        ])->andReturn($response);

        // Act
        $controller = new GitHubController;
        $controller->commitsWebhook($telegram, $espinoso);

        // Assert
        $this->assertDatabaseHas('settings', [
            'key' => 'github_last_event',
            'value' => '2018-01-19T01:32:33Z'
        ]);
    }
}
