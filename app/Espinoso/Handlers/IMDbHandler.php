<?php namespace App\Espinoso\Handlers;

use Imdb\Title;
use Spatie\Emoji\Emoji;
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

    protected $signature   = "espi imdb|movie|peli|serie|tv <cosa a buscar>";
    protected $description = "busco pelis y series, vieja!";

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
    public function handle(Message $message): void
    {
        $types  = $this->parseTypes($this->matches['type']);
        $result = $this->getData($this->matches['query'], $types);

        if (empty($result)) {
            $this->replyError();
            return;
        }

        $matching = $result[0];

        if (!empty($matching->photo())) {
            $this->espinoso->replyImage($matching->photo(), $matching->title());
        }

        $this->espinoso->reply($this->parseAsMarkdown($matching));
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
        $star = Emoji::whiteMediumStar();
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
