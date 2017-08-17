<?php namespace App\Espinoso\Handlers;

use stdClass;
use App\Facades\GoutteClient;
use Telegram\Bot\Objects\Message;

class NextHolidaysHandler extends EspinosoCommandHandler
{
    /**
     * @var string
     */
    protected $pattern = "(\b(pr(o|ó)x(imo(s?))?)\b\s+)?(\b(feriado(s?))\b)$";

    protected $signature   = "espi feriados";
    protected $description = "feriados para rascarse la pelusa";


    public function handle(Message $message): void
    {
        $holidays = collect($this->getHolidays());
        $count = $holidays->count();
        $list = $holidays->map(function (stdClass $holiday) {
            return " - *{$holiday->phrase}*, {$holiday->description} ({$holiday->count} días)";
        })->implode("\n");

        $text = "Manga de vagos, *quedan {$count} feriados* en todo el año.\n{$list}";

        $this->telegram->sendMessage([
            'chat_id' => $message->getChat()->getId(),
            'text' => $text,
            'parse_mode' => 'Markdown'
        ]);
    }

    /**
     * Método dedicado a Dan. Chorea data de elproximoferiado.com y de algún modo saca
     * un json que tienen guardado en un <script> y lo transforma en objects.
     *
     * @return array
     */
    private function getHolidays()
    {
        $crawler = GoutteClient::request('GET', config('espinoso.url.holidays'));

        // here starts crap
        $data = str_replace("\n", "", $crawler->filter('script')->eq(2)->text());
        $data = str_replace("\t", "", $data);
        $data = str_replace("var json = '", '', $data);
        $data = str_replace("';var position = 0;", '', $data);
        // here finishes crap

        return json_decode($data);
    }
}
