<?php

namespace App\Console\Commands;

class HiCommand extends EspiCommand
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
        $messages = collect(__('messages.hi'));
        $this->espinoso->sendToCactus($messages->random());
    }
}
