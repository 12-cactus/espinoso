<?php namespace Tests\Espinoso\Handlers;

use Mockery;
use Imdb\Title;
use App\Facades\IMDbSearch;
use App\Espinoso\Handlers\IMDbHandler;

class IMDbHandlerTest extends HandlersTestCase
{
    /**
     * @test
     */
    public function it_should_handle_when_match_regex()
    {
        // Arrange
        $handler = $this->makeHandler();
        $updates = [
            $this->makeMessage(['text' => 'espi imdb game of thrones']),
            $this->makeMessage(['text' => 'espi movie game of thrones']),
            $this->makeMessage(['text' => 'espi peli game of thrones']),
            $this->makeMessage(['text' => 'espi serie game of thrones']),
            $this->makeMessage(['text' => 'espi tv game of thrones']),
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
            $this->makeMessage(['text' => 'espiimdb game of thrones']),
            $this->makeMessage(['text' => 'espi imdbgame of thrones']),
            $this->makeMessage(['text' => 'movie game of thrones']),
            $this->makeMessage(['text' => 'peli game of thrones']),
            $this->makeMessage(['text' => 'serie game of thrones']),
            $this->makeMessage(['text' => 'tv game of thrones']),
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
        $mockedTitle = Mockery::mock(Title::class);
        $mockedTitle->shouldReceive('storyline')->andReturn('storyline');
        $mockedTitle->shouldReceive('cast')->andReturn([['name' => 'Jon Snow']]);
        $mockedTitle->shouldReceive('genres')->andReturn(['Drama']);
        $mockedTitle->shouldReceive('seasons')->andReturn(8);
        $mockedTitle->shouldReceive('director')->andReturn([['name' => 'Alan Taylor']]);
        $mockedTitle->shouldReceive('creator')->andReturn([['name' => 'David Benioff']]);
        $mockedTitle->shouldReceive('writing')->andReturn([['name' => 'D.B. Weiss']]);
        $mockedTitle->shouldReceive('title')->andReturn('Game of Thrones');
        $mockedTitle->shouldReceive('year')->andReturn(2011);
        $mockedTitle->shouldReceive('rating')->andReturn(9.5);
        $mockedTitle->shouldReceive('runtime')->andReturn(57);
        $mockedTitle->shouldReceive('main_url')->andReturn('http://www.imdb.com/title/tt0944947/');
        $mockedTitle->shouldReceive('photo')->andReturn('https://images-na.ssl-images-amazon.com/;images/M/MV5BMjE3NTQ1NDg1Ml5BMl5BanBnXkFtZTgwNzY2NDA0MjI@._V1_UX182_CR0,0,182,268_AL_.jpg');
        IMDbSearch::shouldReceive('search')->once()
            ->with('game of thrones', [Title::MOVIE, Title::TV_SERIES])
            ->andReturn([$mockedTitle]);

        $photo = 'https://images-na.ssl-images-amazon.com/;images/M/MV5BMjE3NTQ1NDg1Ml5BMl5BanBnXkFtZTgwNzY2NDA0MjI@._V1_UX182_CR0,0,182,268_AL_.jpg';
        $caption = 'Game of Thrones';
        $text = "*Game of Thrones* (2011)
⭐ 9.5/10 | 57min
_Drama_

storyline

*Seasons:* 8
*Creators:* David Benioff
*Writers:* D.B. Weiss
*Directors:* Alan Taylor
*Cast:* Jon Snow

[View on IMDb](http://www.imdb.com/title/tt0944947/)";

        $this->espinoso->shouldReceive('replyImage')->once()->with($photo, $caption);
        $this->espinoso->shouldReceive('reply')->once()->with($text);
        $handler = $this->makeHandler();
        $update = $this->makeMessage(['text' => 'espi imdb game of thrones']);

        // Act
        $handler->shouldHandle($update);
        $handler->handle($update);
    }

    /**
     * @return IMDbHandler
     */
    protected function makeHandler(): IMDbHandler
    {
        return new IMDbHandler($this->espinoso);
    }
}
