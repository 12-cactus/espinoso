<?php

namespace App\Console\Commands;

class DracarysCommand extends EspiCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'espi:dracarys';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dracarys';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $gif = collect(trans('gifs.patterns'))->first(function ($gif) {
            return $gif['video'] == 'dracarys.mp4';
        });

        $this->espinoso->sendGifToCactus(public_path('gifs/'.$gif['video']));
     }
}
