<?php namespace App\Espinoso\Handlers;

use App\Espinoso\Helpers\Msg;
use App\Espinoso\Handlers\ImdbScraper\Imdb;
use Telegram\Bot\Objects\Message;

class ImdbHandler extends EspinosoHandler
{
    const KEYWORD = 'imdb';

    public function shouldHandle(Message $message): bool
    {
        return preg_match($this->regex(), $message->getText());
    }

    public function handle(Message $message)
    {
        $response = $this->buildResponse($message->getText());
        $response = Msg::md($response);
        return $this->telegram->sendMessage( $response->build($message) );
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