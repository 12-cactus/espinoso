<?php namespace App\Espinoso\Handlers;

use App\Espinoso\Handlers\ImdbScraper\Imdb;
use App\Facades\GuzzleClient;
use Imdb\Title;
use stdClass;
use Telegram\Bot\Objects\Message;

class ImdbHandler extends EspinosoCommandHandler
{
    protected $pattern = "(?'type'\b(imdb|movie|peli|serie|tv)\b)(?'query'.+)";
    protected $type = [
        'imdb'  => [Title::MOVIE, Title::TV_SERIES],
        'movie' => [Title::MOVIE],
        'peli'  => [Title::MOVIE],
        'serie' => [Title::TV_SERIES],
        'tv'    => [Title::TV_SERIES],
    ];

    public function handle(Message $message)
    {
        $type   = $this->parseType($this->matches['type']);
        $result = $this->getData($this->matches['query'], $type);

        if (empty($result)) {
            $this->replyError($message);
        }

        if (!empty($result->$result->photo())) {
            $this->telegram->sendPhoto([
                'chat_id' => $message->getChat()->getId(),
                'photo'   => $result->photo(),
                'caption' => $result->title()
            ]);
        }

        $this->telegram->sendMessage([
            'chat_id' => $message->getChat()->getId(),
            'text'    => $this->parseAsMarkdown($result),
            'parse_mode' => 'Markdown',
        ]);
    }

    /*
     * Internals
     */

    protected function parseAsMarkdown(Title $result)
    {
        $cast = collect($result->cast())->take(3)->implode(', ');
        $text = "**{$result->title()}** ({$result->year()})

{$result->storyline()}

**Year:** {$result->year()}
**Cast:** {$cast}

[View on IMDB]({$result->main_url()})
";
    }

    private function extractName($message)
    {
        preg_match($this->regex(), $message, $matches);
        return $matches['name'];
    }


    private function regex()
    {
        return "/^" . self::KEYWORD . "[ ]*(?'name'.*)$/i";
    }

    /**
     * @param $text
     * @return string
     */
    public function buildResponse(string $text): string
    {
        $name = $this->extractName($text);
        $data = $this->movieInfo($name);

        $msg = "```
{$data['TITLE']} 
{$data['IMDB_URL']}
Rating: {$data['RATING']}
Plot: {$data['PLOT']}
Release: {$data['RELEASE_DATES'][0]}```"; 
       
        return $msg;
    }

    /**
     * @param $name
     * @return mixed
     */
    private function movieInfo($name)
    {
        $imdb = new Imdb;
        $output = $imdb->getMovieInfo($name);

        return array_change_key_case($output, CASE_UPPER);
    }
}