<?php namespace App\Espinoso\Handlers;


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
        ],
    ];

    protected $ignorePrefix = true;
    protected $signature   = "[espi] #tag <text>
[espi] list|listar|show|ver #tag
[espi] tags
[espi] clean|clear|limpiar|vaciar #tag";
    protected $description = "cosas con los tags";

    public function handleSetItem(): void
    {
        $tag = $this->matches['tag'];
        $item = $this->matches['item'];

        $tag = Tag::firstOrCreate([
            'telegram_chat_id' => $this->message->getChat()->getId(),
            'name' => $tag
        ]);

        TagItem::firstOrCreate([
            'tag_id' => $tag->id,
            'text' => $item
        ]);

        $this->espinoso->reply(trans('messages.tags.saved', ['tag' => $tag->name]));
    }

    public function handleItemsList(): void
    {
        $tag = $this->matches['tag'];

        $items = Tag::whereName($tag)->first()->items;

        $items = $items->map(function (TagItem $item) {
            return "- {$item->text}";
        })->implode("\n");

        $this->espinoso->reply(trans('messages.tags.items', compact('tag', 'items')));
    }
}
