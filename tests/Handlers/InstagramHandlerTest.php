<?php

namespace Tests\Handlers;

use App\Facades\InstagramSearch;
use App\Espinoso\Handlers\InstagramHandler;

class InstagramHandlerTest extends HandlersTestCase
{
    /**
     * @test
     */
    public function it_should_handle_when_match_regex()
    {
        // Arrange
        $handler = $this->makeHandler();
        $updates = [
            $this->makeMessage(['text' => 'espi ig alanmtk']),
            $this->makeMessage(['text' => 'ig alanmtk']),
            $this->makeMessage(['text' => 'espi ig alanmtk last']),
            $this->makeMessage(['text' => 'ig alanmtk pos:2']),
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
            $this->makeMessage(['text' => 'espiig alanmtk']),
            $this->makeMessage(['text' => 'ig-alanmtk']),
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
        // Mocking
        $response = [[
            'id' => '1577240841595284729_12860068',
            'code' => 'BXjfDBYhmz5',
            'user' => ['id' => '12860068'],
            'images' => [
                'standard_resolution' => [
                    'width' => 640,
                    'height' => 640,
                    'url' => 'https://instagram.fsst1-1.fna.fbcdn.net/t51.2885-15/s640x640/sh0.08/e35/20687889_2047983445218577_9133972975488335872_n.jpg'
                ]
            ]
        ]];

        InstagramSearch::shouldReceive('get')
            ->with('alanmtk')
            ->andReturn($response);

        $photo = 'https://instagram.fsst1-1.fna.fbcdn.net/t51.2885-15/s640x640/sh0.08/e35/20687889_2047983445218577_9133972975488335872_n.jpg';
        $caption = 'Ver https://www.instagram.com/alanmtk';
        $this->espinoso->shouldReceive('replyImage')->once()->with($photo, $caption);

        $handler = $this->makeHandler();
        $update = $this->makeMessage([
            'chat' => ['id' => 123],
            'text' => 'ig alanmtk last'
        ]);

        // Act
        $handler->shouldHandle($update);
        $handler->handle($update);
        $this->assertTrue(true);
    }

    /**
     * @return InstagramHandler
     */
    protected function makeHandler(): InstagramHandler
    {
        return new InstagramHandler($this->espinoso);
    }
}
