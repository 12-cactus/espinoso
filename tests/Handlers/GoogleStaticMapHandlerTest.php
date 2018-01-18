<?php

namespace Tests\Handlers;

use App\Espinoso\Handlers\GoogleStaticMapsHandler;

class GoogleStaticMapHandlerTest extends HandlersTestCase
{
    /**
     * @test
     */
    public function it_should_handle_when_match_regex()
    {
        // Arrange
        $handler = $this->makeHandler();
        $updates = [
            $this->makeMessage(['text' => 'espi gsm islas malvinas']),
            $this->makeMessage(['text' => 'espi gsm z:10 islas malvinas']),
            $this->makeMessage(['text' => 'espi gsm zoom:12 islas malvinas']),
            $this->makeMessage(['text' => 'espi gsm zoom:12 color:blue islas malvinas']),
            $this->makeMessage(['text' => 'espi gsm z:12 c:yellow s:100x100 islas malvinas']),
            $this->makeMessage(['text' => 'gsm z:12 c:yellow s:100x100 islas malvinas']),
            $this->makeMessage(['text' => 'gsm zoom:12 color:blue islas malvinas']),
            $this->makeMessage(['text' => 'gsm zoom:12 islas malvinas']),
            $this->makeMessage(['text' => 'gsm z:12 islas malvinas']),
            $this->makeMessage(['text' => 'gsm islas malvinas']),
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
            $this->makeMessage(['text' => 'espigsm nup']),
            $this->makeMessage(['text' => 'espi gsmnup']),
            $this->makeMessage(['text' => 'espinosogsm tampoco']),
            $this->makeMessage(['text' => 'espinoso gsmtampoco']),
            $this->makeMessage(['text' => 'gsmz:10 nup']),
        ];

        // Act && Assert
        collect($updates)->each(function ($update) use ($handler) {
            $this->assertFalse($handler->shouldHandle($update));
        });
    }

    /**
     * @test
     */
    public function it_handle_and_return_argentinas()
    {
        // Arrange
        $address = 'islas malvinas';
        $options = "maptype=roadmap&zoom=10&size=600x500&markers=color:yellow|label:X|{$address}";
        $photo   = config('espinoso.url.map') . "?center=" . urlencode($address) . "&{$options}";

        $this->espinoso->shouldReceive('replyImage')->once()->with($photo, "{$address}, Argentinas!");
        $handler = $this->makeHandler();
        $update = $this->makeMessage(
            ['text' => 'espi gsm z:10 color:yellow islas malvinas',
        ]);

        // Act
        $handler->shouldHandle($update);
        $handler->handle($update);
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function it_handle_and_return_maps()
    {
        // Arrange
        $address = 'islas malvinas';
        $options = "maptype=roadmap&zoom=10&size=600x500&markers=color:yellow|label:X|{$address}";
        $photo   = config('espinoso.url.map') . "?center=" . urlencode($address) . "&{$options}";

        $this->espinoso->shouldReceive('replyImage')->once();
        $handler = $this->makeHandler();
        $update = $this->makeMessage([
            ['text' => 'gsm z:17 Quilmes'],
        ]);

        // Act
        $handler->shouldHandle($update);
        $handler->handle($update);
        $this->assertTrue(true);
    }

    /**
     * @return GoogleStaticMapsHandler
     */
    protected function makeHandler(): GoogleStaticMapsHandler
    {
        return new GoogleStaticMapsHandler($this->espinoso);
    }
}
