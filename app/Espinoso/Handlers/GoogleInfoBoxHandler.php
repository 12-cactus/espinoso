<?php

namespace App\Espinoso\Handlers;

use App\Espinoso\Helpers\Msg;
use Goutte\Client;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;
use Telegram\Bot\Laravel\Facades\Telegram;

class GoogleInfoBoxHandler extends EspinosoHandler
{
    const KEYWORD = 'gib';

    public function shouldHandle($updates, $context = null)
    {
        return $this->isTextMessage($updates) && preg_match($this->regex(), $updates->message->text) ;
    }

    public function handle($updates, $context = null)
    {
        $response = $this->buildResponse($updates->message->text);

        $imgs = $response['images'] ;
        if (!empty($imgs))
        Telegram::sendPhoto([
            'chat_id' => $updates->message->chat->id,
            'photo' => $imgs[0],
            // 'caption' => ''
        ]);

        $message = '```' . $response['message'] . '```';
        Telegram::sendMessage(Msg::md($message)->build($updates));
    }

    public function buildResponse($text)
    {
        $criteria = rawurlencode($this->getCriteria($text));
        $client = new Client();
        $crawler = $client->request('GET', 'https://www.google.com.ar/search?q=' . $criteria);
        $xpdopen = $crawler->filter('#rhs_block');
        $result['message'] = implode("\n", $this->getText($xpdopen));
        $result['imgs'] = $this->getImages($xpdopen);
        return $result;
    }

    public function regex()
    {
        return "/gib (?'criteria'.*)$/i";
    }

    private function getCriteria($text)
    {
        preg_match($this->regex(), $text, $matches);
        return $matches['criteria'];
    }

    private function getText(Crawler $node)
    {
        return $node->filter('._o0d')->each(function($div) 
        {
            $result = '';
            $left = $right = ""; 
            foreach ($this->keyValueSelectors() as $key => $value)
            {
                if (! is_null($key) && $div->filter($key)->count() > 0) 
                    $left = $div->filter($key)->first()->text() ; 

                if ( ! is_null($value) && $div->filter($value)->count() > 0) 
                    $right = $div->filter($value)->first()->text() ; 
            }
            $result = "$left: $right"; 
            return $result; 
        });
    }

    private function getImages(Crawler $node)
    {
        return $node->filter('img')->each(function($tag) {
            return $tag->attr('src');
        });
    }

    private function keyValueSelectors()
    {
        return [
            '._B5d' => '._zdb',
            '.fl'   => '._Fng',
            '._tXc' =>  null , 
            '._gS'  => '._tA', 
        ];
    }

}