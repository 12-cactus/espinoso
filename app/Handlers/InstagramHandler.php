<?php

namespace App\Handlers;
//require __DIR__.'/../vendor/autoload.php';
use Exception;


class InstagramHandler extends BaseCommand
{
    protected $ignorePrefix = true;
    protected $pattern = "(\b(ig)\b)(\s+)(?'username'\b(\S+)\b)(?'param'\s*(last|pos:\d+))?$";

    protected $signature   = "[espi] ig username [last|pos:n]";
    protected $description = "y... fijate";

    public function handle(): void
    {
        $ig = new \InstagramAPI\Instagram(false, false);
        $maxId = null;
        try {
            $ig->login('espinoso.cactus', '12cactus21');
        } catch (Exception $e) {
            $this->replyNotFound();
        }
        $userName = trim($this->matches['username']);
        $userId = $ig->people->getUserIdForName($userName);
        $response = $ig->timeline->getUserFeed($userId, $maxId);
        $items = $response->getItems();
        $photos = collect($items)->map(function ($item){
            return $item->getImageVersions2()->getCandidates()[0]->getUrl();
        });
        dump($photos);
        $this->espinoso->replyImage($this->getImage($photos, $this->getParam()));
    }

    protected function getParam()
    {
        if (empty($this->matches['param'])) {
            return 'random';
        }

        $param = trim($this->matches['param']);

        if ($param == 'last') {
            return 0;
        }

        if (starts_with($param, 'pos:')) {
            return intval(explode(':', $param)[1]);
        }

        return $param;
    }

    protected function getImage($listPhoto, $param)
    {
        $i = $param === 'random' ? rand(0, count($listPhoto) - 1) : intval($param);

        return $listPhoto[$i];
    }
}
