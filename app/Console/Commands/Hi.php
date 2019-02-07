<?php

namespace App\Console\Commands;

class Hi extends EspiCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'espi:hi';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Espi saluda al pueblo';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $messages = collect([
            'Sabakuskas Botón!',
            'Facu puto',
            'Hola lunarcito',
            'Maru mamasitaaaa',
            'Anita amarillita jajaja',
            'Pauuuulaaaaa doooonde eeeeeestaaaaaas??',
            'Alan extraño tus manos en mi código',
            'OPM Botón!!',
            'Agus careta',
            'Markis esa panza sigue creciendo?',
            'Sir, como anda la cope "dibase"?',
            'Ine garca, no me querés programar',
            'Pipi seguís tan puto como siempre?',
            'El viejo wyry! cómo va old man?'
        ]);
        $this->espinoso->sendMessage(config('espinoso.12c'), $messages->random());
    }
}
