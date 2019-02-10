<?php

namespace App\Handlers;

use App\Facades\GoutteClient;
use Symfony\Component\DomCrawler\Crawler;

class GoogleInfoBoxHandler extends BaseCommand
{
    /**
     * @var string
     */
    protected $ignorePrefix = true;
    /**
     * @var string
     */
    protected $pattern = "(?'i'\b(info)\b)(?'query'.+)$";

    protected $signature   = "[espi] info <cosa a buscar>";
    protected $description = "trato de traer data";

    public function handle(): void
    {
        $response = $this->buildResponse(rawurlencode(trim($this->matches['query'])));
        $content = collect(explode("\n", $response['message']));
        $images = collect($response['images']);

        if ($images->isNotEmpty()) {
            $this->espinoso->replyImage($images->first(), $content->shift());
        }

        $text = trim($content->implode("\n"));

        if (empty($text)) {
            $this->replyNotFound();
            return;
        }

        $this->espinoso->reply($text);
    }

    /**
     * @param string $query
     * @return mixed
     */
    public function buildResponse(string $query)
    {
        $crawler = GoutteClient::request('GET', config('espinoso.url.info') . $query);
        $block = $crawler->filter('#rhs_block');

        $message = array_filter($this->getText($block), function ($text) {
            return !is_null($text);
        });

        $result['message'] = implode("\n", $this->tuning($message));
        $result['images'] = $this->getImages($block);
        return $result;
    }

    private function getText(Crawler $node)
    {
        return $node->filter('._o0d')->each(function ($div) {
            $left = $right = "";

            foreach ($this->keyValueSelectors() as $key => $value) {
                if (! is_null($key) && $div->filter($key)->count() > 0) {
                    $left = $div->filter($key)->first()->text();
                }

                if (! is_null($value) && $div->filter($value)->count() > 0) {
                    $right = $div->filter($value)->first()->text();
                }
            }

            if (empty($left) && empty($right)) {
                return null;
            }

            if (empty($left)) {
                return $right;
            }

            if (empty($right)) {
                return $left;
            }
            
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
        return $node->filter('img')->each(function ($tag) {
            return $tag->attr('src');
        });
    }

    private function keyValueSelectors()
    {
        return [
            '._B5d' => '._zdb',
            '.fl'   => '._Fng',
            '._tXc' =>  null,
            '._gS'  => '._tA',
        ];
    }
}
