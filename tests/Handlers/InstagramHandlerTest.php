<?php

namespace Tests\Handlers;

use App\Facades\InstagramSearch;
use App\Handlers\InstagramHandler;

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
            $this->makeMessage(['text' => 'espi ig maricruz.gil']),
            $this->makeMessage(['text' => 'ig maricruz.gil']),
            $this->makeMessage(['text' => 'espi ig maricruz.gil last']),
            $this->makeMessage(['text' => 'ig maricruz.gil pos:2']),
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
            $this->makeMessage(['text' => 'espiig maricruz.gil']),
            $this->makeMessage(['text' => 'ig-maricruz.gil']),
        ];

        // Act && Assert
        collect($updates)->each(function ($update) use ($handler) {
            $this->assertFalse($handler->shouldHandle($update));
        });
    }

    /**
     * @test
     */
/*
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
                    'url' => 'https://instagram.faep6-1.fna.fbcdn.net/vp/3b1a914721f478298940219657910a11/5CF65A25/t51.2885-15/e35/47694397_324083524869771_7854774045045812352_n.jpg?_nc_ht=instagram.faep6-1.fna.fbcdn.net&se=7&ig_cache_key=MTk1Nzc3NTE5ODU3NDc2Njg1OQ%3D%3D.2'
                ]
            ]
        ]];

        InstagramSearch::shouldReceive('get')
            ->with('maricruz.gil')
            ->andReturn($response);

        $photo = 'https://instagram.faep6-1.fna.fbcdn.net/vp/3b1a914721f478298940219657910a11/5CF65A25/t51.2885-15/e35/47694397_324083524869771_7854774045045812352_n.jpg?_nc_ht=instagram.faep6-1.fna.fbcdn.net&se=7&ig_cache_key=MTk1Nzc3NTE5ODU3NDc2Njg1OQ%3D%3D.2';
        $this->espinoso->shouldReceive('replyImage')->once()->with($photo);

        $handler = $this->makeHandler();
        $update = $this->makeMessage([
            'chat' => ['id' => 123],
            'text' => 'ig maricruz.gil last'
        ]);

        // Act
        $handler->shouldHandle($update);
        $handler->handle($update);
        $this->assertTrue(true);
    }
*/
    /**
     * @return InstagramHandler
     */
    protected function makeHandler(): InstagramHandler
    {
        return new InstagramHandler($this->espinoso);
    }
}
