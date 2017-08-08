<?php

namespace App\Espinoso\Handlers;

use App\Facades\GoutteClient;
use Telegram\Bot\Objects\Message;
use Symfony\Component\DomCrawler\Crawler;

class GoogleInfoBoxHandler extends EspinosoCommandHandler
{
    /**
     * @var string
     */
    protected $allow_ignore_prefix = true;
    /**
     * @var string
     */
    protected $pattern = "(?'i'\binfo\b)(?'query'.+)$";

    public function handle(Message $message)
    {
        $response = $this->buildResponse(rawurlencode(trim($this->matches['query'])));
        $content = collect(explode("\n", $response['message']));
        $images = collect($response['images']);

        if ($images->isNotEmpty()) {
            $title = $content->shift();
            $this->telegram->sendPhoto([
                'chat_id' => $message->getChat()->getId(),
                'photo'   => $images->first(),
                'caption' => $title
            ]);
        }

        $text = trim($content->implode("\n"));
        $text = empty($text)
            ? "Uhhh... no hay un carajo!!\nO buscaste como el orto o estoy haciendo cualquiera!" // FIXME lang!
            : $text;

        $this->telegram->sendMessage([
            'chat_id' => $message->getChat()->getId(),
            'text'    => $text,
            'parse_mode' => 'Markdown',
        ]);
    }

    /**
     * FIXME
     * Content extracted should be more rich than plain.
     * For example, it should keep links as Markdown.
     *
     * @param string $query
     * @return mixed
     */
    public function buildResponse(string $query)
    {
        $crawler = GoutteClient::request('GET', config('espinoso.url.info') . $query);
        $block = $crawler->filter('#rhs_block');
        
        $message = $this->getText($block);
        $message = array_filter($message, function ($text) { return !is_null($text); });

        $result['message'] = implode("\n", $this->tuning($message));
        $result['images'] = $this->getImages($block);
        return $result;
    }

    private function getText(Crawler $node)
    {
        return $node->filter('._o0d')->each(function($div)
        {
            $left = $right = "";
            foreach ($this->keyValueSelectors() as $key => $value)
            {
                if (! is_null($key) && $div->filter($key)->count() > 0) 
                    $left = $div->filter($key)->first()->text();

                if ( ! is_null($value) && $div->filter($value)->count() > 0) 
                    $right = $div->filter($value)->first()->text();
            }
            if (empty($left) && empty($right))
                return null;

            if (empty($left))
                return $right;

            if (empty($right))
                return $left ; 
            
            return "$left: $right"; 
        });
    }

    protected function tuning(array $lines = [])
    {
        // Change "Plataforma: :" to "**Plataforma:**"
        return collect($lines)->map(function ($line) {
            return preg_replace('/(.+)(:\s:)/', '*${1}:*', $line);
        })->toArray();
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
