<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Model\Schedule;

class ScheduleCommand extends EspiCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'espi:agenda';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mensajes por los eventos de la agenda (si corresponde)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // FIXME por supuesto que esto habría que hacerlo un poco más decente, pero a mi me da paja. - OPM.
        $today = Carbon::today();
        $events = collect([
            ['day' => '06-01', 'message' => 'Feliz cumple Pipi!! Que coman rico asado con los de videojuegos!'],
            ['day' => '21-01', 'message' => 'Feliz cumple Alan!! Nadie me metió mano como vos!'],
            ['day' => '10-02', 'message' => 'Feliz cumple Facu!! Puto! Maybe te quiero, maybe no!'],
            ['day' => '27-02', 'message' => 'Feliz cumple Niñita!! Hoy no acuchilles a nadie!'],
            ['day' => '03-03', 'message' => 'Feliz cumple Agustín!! Eyyyy ojo con tus medio chaboncitos hoy, eh!! Usá funda'],
            ['day' => '22-03', 'message' => 'Feliz cumple Espi!! Que pases un gran día! Ahhh esperen, soy yo, que pelotudo!! Igual soy crack'],
            ['day' => '19-05', 'message' => "Feliz cumple Jotapé!! Vamo' a tomar una birra?"],
            ['day' => '26-05', 'message' => 'Feliz cumple Dan!! El viejo wyry!! Ahora que cobrás en dólares no me querés mas, no?'],
            ['day' => '06-06', 'message' => 'Feliz cumple Sir!! Seguís viejo y puto como siempre?'],
            ['day' => '16-07', 'message' => 'Feliz cumple Sabakuskas!! A ver cuando hacemos un asado en la unqui'],
            ['day' => '12-09', 'message' => 'Feliz cumple Lea!! Sos el OPM más ortiva que conozco, amargo!'],
            ['day' => '25-09', 'message' => 'Feliz cumple Maru!! Mother of cactus! Seguís con ese humor de mierda?'],
            ['day' => '05-11', 'message' => 'Feliz cumple Marki!! Apuesto que hoy esa panza va a crecer y crecer!'],
            ['day' => '21-11', 'message' => 'Feliz cumple Anita!! Ya te hiciste peroncha?'],
        ]);

        $events->filter(function ($event) use ($today) {
            return $event['day'] == $today->format('d-m');
        })->each(function ($event) {
            $this->espinoso->sendToCactus($event['message']);
        });

        if ($today->dayOfYear === 256) {
            $this->espinoso->sendToCactus("Feliz día monos tecleadores!!");
        }
    }
}
