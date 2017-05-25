<?php
/**
 * Created by PhpStorm.
 * User: prospero
 * Date: 5/24/17
 * Time: 11:30 PM
 */

namespace App\Espinoso\Handlers;

use \App\Espinoso\Handlers\ImdbScraper\Imdb;
use App\Espinoso\Helpers\Msg;

class ImdbHandler extends EspinosoHandler
{
    const KEYWORD = 'imdb';

    public function shouldHandle($updates, $context=null)
    {
        return  $this->isTextMessage($updates) && preg_match($this->regex(), $updates->message->text);
    }

    public function handle($updates, $context=null)
    {
        $response = $this->buildResponse($updates->message->text);
        $response = Msg::md($msg);
        return Telegram::sendMessage( $response->build($updates) );
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
     * @param $updates
     */
    public function buildResponse($text)
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
     * @param $imdb
     * @param $name
     * @return mixed
     */
    private function movieInfo($name)
    {
        $imdb = new Imdb();
        $output = $imdb->getMovieInfo($name);

        return array_change_key_case($output, CASE_UPPER);
    }


}