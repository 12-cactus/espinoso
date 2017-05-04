<?php

namespace App\Espinoso\Handlers;

use App\Espinoso\Helpers\Msg;
use Goutte\Client;
use Illuminate\Support\Str;
use Telegram\Bot\Laravel\Facades\Telegram;

class CinemaHandler extends EspinosoHandler
{

    public function shouldHandle($updates, $context = null)
    {
        return $this->isTextMessage($updates) && preg_match('/cine.?$/i', $updates->message->text) ;
    }

    public function handle($updates, $context = null)
    {
        $client = new Client();
        $crawler = $client->request('GET', 'http://www.hoyts.com.ar/ajaxCartelera.aspx?filter=Home&cine=&premium=False&_=1493929858090');

        $movies = [];

        $crawler->filter('.title > a')->each(function ($node) use (&$movies) {
            $movies[] = Str::ucfirst(Str::lower($node->text()));
        });

        $message = "La pensas poner? Mete Netflix pelotud@! es mas barato! Pero igual podes ver todas estas: \n" . implode("\n", $movies);
        Telegram::sendMessage(Msg::plain($message)->build($updates));
    }
}