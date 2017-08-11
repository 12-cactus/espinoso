<?php namespace App\Espinoso\Handlers;

use Imdb\Title;
use App\Facades\IMDbSearch;
use Telegram\Bot\Objects\Message;

/**
 * Class IMDbHandler
 * @package App\Espinoso\Handlers
 */
class IMDbHandler extends EspinosoCommandHandler
{
    /**
     * @var string
     */
    protected $pattern = "(?'type'\b(imdb|movie|peli|serie|tv)\b)(?'query'.+)";
    /**
     * @var array
     */
    protected $types = [
        'imdb'  => [Title::MOVIE, Title::TV_SERIES],
        'movie' => [Title::MOVIE],
        'peli'  => [Title::MOVIE],
        'serie' => [Title::TV_SERIES],
        'tv'    => [Title::TV_SERIES],
    ];

    /**
     * @param Message $message
     */
    public function handle(Message $message)
    {
        $types  = $this->parseTypes($this->matches['type']);
        $result = $this->getData($this->matches['query'], $types);

        if (empty($result)) {
            $this->replyError($message);
            return;
        }

        $matching = $result[0];

        if (!empty($matching->photo())) {
            $this->telegram->sendPhoto([
                'chat_id' => $message->getChat()->getId(),
                'photo'   => $matching->photo(),
                'caption' => $matching->title()
            ]);
        }

        $this->telegram->sendMessage([
            'chat_id' => $message->getChat()->getId(),
            'text'    => $this->parseAsMarkdown($matching),
            'parse_mode' => 'Markdown',
        ]);
    }

    /*
     * Internals
     */

    /**
     * @param string $key
     * @return array
     */
    protected function parseTypes(string $key)
    {
        $types = collect($this->types);

        return $types->has($key) ? $types->get($key) : [];
    }

    /**
     * @param string $query
     * @param array $types
     * @return mixed
     */
    protected function getData(string $query, array $types = [])
    {
        return IMDbSearch::search(trim($query), $types);
    }

    /**
     * @param Title $result
     * @return string
     */
    protected function parseAsMarkdown(Title $result)
    {
        $star = "\u{2B50}";
        $sinopsis  = str_limit(trim($result->storyline()), 250);
        $cast      = collect($result->cast())->take(3)->pluck('name')->implode(', ');
        $genres    = collect($result->genres())->implode(', ');
        $seasons   = $result->seasons() > 0 ? "\n*Seasons:* {$result->seasons()}" : '';
        $directors = collect($result->director())->take(3)->pluck('name')->implode(', ');
        $creators  = empty($result->creator())
            ? ''
            : "\n*Creators:* " . collect($result->creator())->take(3)->pluck('name')->implode(', ');
        $writers   = collect($result->writing())->take(3)->pluck('name')->implode(', ');

        return "*{$result->title()}* ({$result->year()})
{$star} {$result->rating()}/10 | {$result->runtime()}min
_{$genres}_

{$sinopsis}
{$seasons}{$creators}
*Writers:* {$writers}
*Directors:* {$directors}
*Cast:* {$cast}

[View on IMDb]({$result->main_url()})";
    }
}
