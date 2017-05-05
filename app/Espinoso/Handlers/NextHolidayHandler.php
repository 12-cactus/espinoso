<?php
/**
 * Created by PhpStorm.
 * User: alan
 * Date: 5/5/17
 * Time: 6:56 PM
 */

namespace App\Espinoso\Handlers;


use App\Espinoso\Helpers\Msg;
use Goutte\Client;
use Telegram\Bot\Laravel\Facades\Telegram;

class NextHolidayHandler extends EspinosoHandler
{

    public function shouldHandle($updates, $context = null)
    {
        return $this->isTextMessage($updates) && preg_match('/feriado.?$/i', $updates->message->text);
    }

    public function handle($updates, $context = null)
    {
        $client = new Client();
        $crawler = $client->request('GET', 'http://www.elproximoferiado.com/');

        $date = $crawler->filter('#fecha')->first()->text();
        $reason = $crawler->filter('#motivo')->first()->text();

        $message = 'Manga de vagos, el próximo feriado es el ' . $date . ' con motivo ' . $reason;

        Telegram::sendMessage(Msg::plain($message)->build($updates));
    }
}