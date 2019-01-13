<?php

namespace App\Handlers;

use App\Model\Tag;
use App\Model\TagItem;

class TagsHandler extends MultipleCommand
{
    /**
     * @var string
     */
    protected $patterns = [
        [
            'name' => 'set-item',
            'pattern' => "(?'tag'#\w+)\s+(?'item'.+)$"
        ],[
            'name' => 'items-list',
            'pattern' => "((list|listar|show|ver)\s+)(?'tag'#\w+)\s*$"
        ],[
            'name' => 'tags-list',
            'pattern' => "(?'key'tags)\s*$"
        ],[
            'name' => 'clear-tag',
            'pattern' => "((clean|clear|limpiar|vaciar)\s+)(?'tag'#\w+)\s*$"
        ],[
            'name' => 'delete-item',
            'pattern' => "((delete)\s+)(?'tag'#\w+)\s+(?'item'.+)$"
        ],
    ];

    protected $ignorePrefix = true;
    protected $signature   = "[espi] #tag <text>
[espi] list|listar|show|ver #tag
[espi] tags
[espi] clean|clear|limpiar|vaciar #tag";
    protected $description = "cosas con los tags";
    protected $tag_id;

    protected function handleSetItem(): void
    {
        $tag = $this->matches['tag'];
        $item = $this->matches['item'];

        $item = explode('.', $item);

        $this->tag_id = Tag::firstOrCreate([
            'telegram_chat_id' => $this->message->getChat()->getId(),
            'name' => $tag
        ]);

        foreach ($item as $value) {
            TagItem::firstOrCreate([
                'tag_id' => $this->tag_id->id,
                'text' => $value
            ]);
        };

        $this->replyOk();
    }

    protected function handleItemsList(): void
    {
        $tag = $this->matches['tag'];

        $items = Tag::whereName($tag)
            ->whereTelegramChatId($this->message->getChat()->getId())
            ->first();

        if ($items == null) {
            $this->replyNotFound();
            return;
        }

        $items = $items->items;

        $items = $items->map(function (TagItem $item) {
            return "- {$item->text}";
        })->implode("\n");

        $this->espinoso->reply(trans('messages.tags.items', compact('tag', 'items')));
    }

    protected function handleTagsList(): void
    {
        $tags = Tag::whereTelegramChatId($this->message->getChat()->getId())->get();

        $tags = $tags->map(function (Tag $tag) {
            return "- {$tag->name}";
        })->implode("\n");

        if (empty($tags)) {
            $this->replyNotFound();
            return;
        }

        $this->espinoso->reply(trans('messages.tags.tags', compact('tags')));
    }

    protected function handleClearTag(): void
    {
        $tag = $this->matches['tag'];
        $tag = Tag::whereName($tag)
            ->whereTelegramChatId($this->message->getChat()->getId())
            ->first();

        if (!empty($tag)) {
            $tag->items()->delete();
            $tag->delete();
        }

        $this->replyOk();
    }

    protected function handleDeleteItem(): void
    {
        $tag = $this->matches['tag'];
        $tag = Tag::whereName($tag)
            ->whereTelegramChatId($this->message->getChat()->getId())
            ->get();

        $textItem = $this->matches['item'];

        TagItem::whereText($textItem)
            //->whereTagId($tag->name)
            ->delete();

        $this->replyOk();
    }
}
