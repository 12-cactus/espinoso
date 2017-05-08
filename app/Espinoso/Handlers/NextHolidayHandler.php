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
        $holidays = $this->getHolidays();

        $message = "Manga de vagos, **quedan " . count($holidays) . " feriados** en todo el año.\n";

        foreach ($holidays as $holiday) {
            $message .= ' - **' . $holiday->phrase . '**, ' . $holiday->description . ' (' . $holiday->count . " días)\n";
        }

        Telegram::sendMessage(Msg::md($message)->build($updates));
    }

    /**
     * Método dedicado a Dan. Chorea data de elproximoferiado.com y de algún modo saca
     * un json que tienen guardado en un <script> y lo transforma en objects.
     *
     * @return array
     */
    private function getHolidays()
    {
        $client = new Client();
        $crawler = $client->request('GET', 'http://www.elproximoferiado.com/');

        // here starts crap
        $data = str_replace("\n", "", $crawler->filter('script')->first()->text());
        $data = str_replace("\t", "", $data);
        $data = str_replace("var json = '", '', $data);
        $data = str_replace("';var position = 0;", '', $data);

        // here finishes crap

        return json_decode($data);
    }
}