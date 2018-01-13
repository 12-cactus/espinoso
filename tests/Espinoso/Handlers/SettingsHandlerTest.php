<?php namespace Tests\Espinoso\Handlers;

use Unisharp\Setting\SettingFacade as Setting;
use App\Espinoso\Handlers\SettingsHandler;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SettingsHandlerTest extends HandlersTestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_should_handle_when_match_regex()
    {
        // Arrange
        $handler = $this->makeHandler();
        $updates = [
            $this->makeMessage(['text' => 'espi get key']),
            $this->makeMessage(['text' => 'get key']),
            $this->makeMessage(['text' => 'espi set key value']),
            $this->makeMessage(['text' => 'set key value']),
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
        $handler = $this->makeHandler();
        $updates = [
            $this->makeMessage(['text' => 'espi get-key']),
            $this->makeMessage(['text' => 'dame key']),
            $this->makeMessage(['text' => 'espi key value']),
            $this->makeMessage(['text' => 'key']),
        ];

        // Act && Assert
        collect($updates)->each(function ($update) use ($handler) {
            $this->assertFalse($handler->shouldHandle($update));
        });
    }

    /**
     * @test
     */
    public function it_handle_set_and_store_it()
    {
        // Mocking
        $this->espinoso->shouldReceive('reply')->once()->with(trans('messages.settings.saved'));

        $handler = $this->makeHandler();
        $update = $this->makeMessage([
            'chat' => ['id' => 123],
            'text' => 'espi set phpunit test blabla'
        ]);

        // Act
        $handler->shouldHandle($update);
        $handler->handle($update);

        $this->assertDatabaseHas('settings', [
            'key' => 123,
            'value' => '{"phpunit":"test blabla"}'
        ]);
    }

    /**
     * @test
     */
    public function it_handle_get_with_unknown_key()
    {
        // Mocking
        $this->espinoso->shouldReceive('reply')->once()->with(trans('messages.search.empty'));

        $handler = $this->makeHandler();
        $update = $this->makeMessage([
            'chat' => ['id' => 123],
            'text' => 'espi get saraza'
        ]);

        // Act
        $handler->shouldHandle($update);
        $handler->handle($update);

        $this->assertDatabaseMissing('settings', [
            'key' => 123
        ]);
    }

    /**
     * @test
     */
    public function it_handle_get_with_valid_key()
    {
        // Mocking & Arrange
        $value = "test";
        $this->espinoso->shouldReceive('reply')->once()->with($value);

        $handler = $this->makeHandler();
        $update = $this->makeMessage([
            'chat' => ['id' => 123],
            'text' => 'espi get phpunit'
        ]);

        // Act
        Setting::set("123.phpunit", $value);
        $handler->shouldHandle($update);
        $handler->handle($update);

        $this->assertDatabaseHas('settings', [
            'key' => 123,
            'value' => '{"phpunit":"test"}'
        ]);
    }

    /**
     * @return SettingsHandler
     */
    protected function makeHandler(): SettingsHandler
    {
        return new SettingsHandler($this->espinoso);
    }
}
