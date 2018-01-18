<?php

namespace Tests\Handlers;

use Mockery;
use App\Model\Tag;
use App\Model\TagItem;
use App\Model\TelegramChat;
use App\Handlers\TagsHandler;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TagsHandlerTest extends HandlersTestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function it_should_handle_when_receives_issue_command()
    {
        // Arrange
        $this->handler = $this->makeHandler();

        // Act & Assert
        $this->assertShouldHandle('espi #list cosa a guardar');
        $this->assertShouldHandle('#list cosa a guardar');

        $this->assertShouldHandle('espi ver #tag');
        $this->assertShouldHandle('espi show #list');
        $this->assertShouldHandle('list #tag');
        $this->assertShouldHandle('listar #list');

        $this->assertShouldHandle('espi tags');
        $this->assertShouldHandle('tags');

        $this->assertShouldHandle('espi clean #tag');
        $this->assertShouldHandle('espi clear #list');
        $this->assertShouldHandle('limpiar #tag');
        $this->assertShouldHandle('vaciar #list');
    }

    /**
     * @test
     */
    public function it_should_not_handle_when_receives_another_text()
    {
        // Arrange
        $this->handler = $this->makeHandler();

        // Act & Assert
        $this->assertShouldNotHandle('espi ver tag');
        $this->assertShouldNotHandle('espi limpiar tag');
    }

    /**
     * @test
     */
    public function it_should_set_item()
    {
        // Mocking
        $this->espinoso
            ->shouldReceive('reply')->once()
            ->with(Mockery::anyOf(...trans('messages.ok')));

        $handler = $this->makeHandler();
        $update = $this->makeMessage([
            'chat' => ['id' => 123],
            'text' => 'espi #tag cosa'
        ]);

        // Act
        $handler->shouldHandle($update);
        $handler->handle($update);

        $this->assertDatabaseHas('tags', [
            'telegram_chat_id' => 123,
            'name' => '#tag'
        ]);
        $this->assertDatabaseHas('tag_items', [
            'id' => 1,
            'text' => 'cosa'
        ]);
    }

    /**
     * @test
     */
    public function it_should_list_items()
    {
        // Mocking && Arrange
        $tag = Tag::firstOrCreate([
            'telegram_chat_id' => 123,
            'name' => '#tag'
        ]);
        $item = TagItem::firstOrCreate([
            'tag_id' => $tag->id,
            'text' => 'cosa'
        ]);
        $tag = $tag->name;
        $items = TagItem::all()->map(function (TagItem $item) {
            return "- {$item->text}";
        })->implode("\n");

        $this->espinoso
            ->shouldReceive('reply')->once()
            ->with(trans('messages.tags.items', compact('tag', 'items')));

        $handler = $this->makeHandler();
        $update = $this->makeMessage([
            'chat' => ['id' => 123],
            'text' => 'espi show #tag'
        ]);

        // Act
        $handler->shouldHandle($update);
        $handler->handle($update);
        $this->assertTrue(true);

        $this->assertEquals('#tag', $item->tag->name);
    }
/**
     * @test
     */
    public function it_should_list_tags()
    {
        // Mocking && Arrange
        $chat = factory(TelegramChat::class)->create();
        $tag = Tag::firstOrCreate([
            'telegram_chat_id' => $chat->id,
            'name' => '#tag'
        ]);
        TagItem::firstOrCreate([
            'tag_id' => $tag->id,
            'text' => 'cosa'
        ]);
        $tags = Tag::all()->map(function (Tag $tag) {
            return "- {$tag->name}";
        })->implode("\n");

        $this->espinoso
            ->shouldReceive('reply')->once()
            ->with(trans('messages.tags.tags', compact('tags')));

        $handler = $this->makeHandler();
        $update = $this->makeMessage([
            'chat' => ['id' => $chat->id],
            'text' => 'espi tags'
        ]);

        // Act
        $handler->shouldHandle($update);
        $handler->handle($update);

        $this->assertEquals(1, $chat->tags->count());
        $this->assertEquals($tag->name, $chat->tags->first()->name);
    }

    /**
     * @test
     */
    public function it_should_clear_tag()
    {
        // Mocking && Arrange
        $tag = Tag::firstOrCreate([
            'telegram_chat_id' => 123,
            'name' => '#tag'
        ]);
        TagItem::firstOrCreate([
            'tag_id' => $tag->id,
            'text' => 'cosa'
        ]);
        $this->espinoso
            ->shouldReceive('reply')->once()
            ->with(Mockery::anyOf(...trans('messages.ok')));

        $handler = $this->makeHandler();
        $update = $this->makeMessage([
            'chat' => ['id' => 123],
            'text' => 'espi clear #tag'
        ]);

        // Act
        $handler->shouldHandle($update);
        $handler->handle($update);
        $this->assertDatabaseMissing('tags', [
            'telegram_chat_id' => 123,
            'name' => '#tag'
        ]);
        $this->assertDatabaseMissing('tag_items', [
            'id' => 1,
            'text' => 'cosa'
        ]);
    }

    /**
     * @return TagsHandler
     */
    protected function makeHandler(): TagsHandler
    {
        return new TagsHandler($this->espinoso);
    }
}
