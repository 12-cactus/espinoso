<?php

namespace App\Handlers;

use App\Facades\GuzzleClient;
use Spatie\Emoji\Emoji;
use stdClass;

class ServiceStatusSubwayHandler extends BaseCommand
{
    protected $pattern = ".{0,100}\b(subte)\b.{0,100}$";
    protected $signature   = "espi subte";
    protected $description = "Te muestro si alguna línea de subte anda para la mierda";

    public function handle(): void
    {
        $crawler = GuzzleClient::request('GET', config('espinoso.url.subway'))->getBody()->getContents();
        $json = collect(json_decode($crawler));

        $list =[];
        foreach ($json as $key => $value) {
            $parse = $this->parseSubway($value);
            array_push($list, "*{$key}* {$parse}");
        }

        $resultList = collect($list)->map(function ($node) {
            return "$node";
        })->implode("\n");
        $metro = Emoji::CHARACTER_METRO;
        $this->espinoso->reply("{$metro} *ESTADO DE LOS SUBTES*\n\n{$resultList}");
    }

    protected function parseSubway(stdClass $node)
    {
        $emoji = $node->text == 'Normal'
            ? Emoji::CHARACTER_WHITE_HEAVY_CHECK_MARK
            : Emoji::CHARACTER_CROSS_MARK;
        return "{$emoji} {$node->text}\n";
    }
}