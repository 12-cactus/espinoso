<?php

namespace App\Handlers;


use App\Facades\GuzzleClient;
use Spatie\Emoji\Emoji;
use Exception;

class TrainHandler extends BaseCommand
{
    protected $pattern = "((tren)\s+)(?'branch'\w+)\s+(?'station'.+)$";
    protected $signature   = "espi tren <ramal> <estación>";
    protected $description = "Hago futurología con el horario del tren. Por ahora solo habilitado ramal roca";
    /*Posibles ramales:
    - roca
    - sarmiento
    - sanmartin
    - mitre
    - belgranosur";
    */

    public function handle(): void
    {
        /*
        $crawler = GuzzleClient::request('GET', config('espinoso.url.train').'/static')->getBody()->getContents();
        $jsonStatic = collect(json_decode($crawler));

        $queryBranch = $this->matches['queryBranch'];
        $ramal = collect($jsonStatic)->filter(function ($value, $key, $queryRamal){
            return ($key='id' && $value=$queryBranch);
        });
*/
        $emojiTrain = Emoji::CHARACTER_TRAIN;
        //try {
            $crawler = GuzzleClient::request('GET', config('espinoso.url.train').'data/11')
                ->getBody()->getContents();
        //} catch (Exception $e) {
        //    $this->espinoso->reply("El servicio este anda para la mierda\n\n".$e->getMessage());
        //}

        $jsonStatic = collect(json_decode($crawler));

        $station = trim($this->matches['station']);
        $jsonStatic = $jsonStatic->get('response');
        $arrivals = collect($jsonStatic)->get('arrivals');
        $arrival = collect($arrivals)->where('nombre', $station);
        $arrival = collect($arrival)->first();
        $laPlata = $this->getDescription($arrival->minutos_1);
        $const = $this->getDescription($arrival->minutos_3);
        $this->espinoso->reply($emojiTrain." *TRENES CONST-LA PLATA*
        \nEstación: {$station}
        \nA La Plata => {$laPlata}\nA Const.    => {$const}");
    }

    private function getDescription($number)
    {
        if ($number < 0) {
            return "Ni puta idea cuando llega";
        } else if ($number === 0) {
            return "En andén";
        } else if ($number === 1) {
            return "llega en 1 minuto";
        } else {
            return "llega en {$number} minutos";
        }
    }
}
