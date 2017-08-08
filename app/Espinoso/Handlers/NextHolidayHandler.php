<?php namespace App\Espinoso\Handlers;

use Goutte\Client;
use App\Espinoso\Helpers\Msg;
use Telegram\Bot\Objects\Message;

class NextHolidayHandler extends EspinosoHandler
{
    public function shouldHandle(Message $message): bool
    {
        return preg_match('/feriado.?$/i', $message->getText());
    }

    public function handle(Message $message)
    {
        $holidays = $this->getHolidays();

        $text = "Manga de vagos, **quedan " . count($holidays) . " feriados** en todo el año.\n";

        foreach ($holidays as $holiday) {
            $text .= ' - **' . $holiday->phrase . '**, ' . $holiday->description . ' (' . $holiday->count . " días)\n";
        }

        $this->telegram->sendMessage(Msg::md($text)->build($message));
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
