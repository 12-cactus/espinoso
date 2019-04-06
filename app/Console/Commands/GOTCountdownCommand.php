<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Spatie\Emoji\Emoji;

class GOTCountdownCommand extends EspiCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'espi:got';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Countdown de GOT';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $cold = Emoji::coldFace();
        $today = Carbon::today();
        $gotDate = Carbon::create(2019, 4, 14);
        $days = $gotDate < $today ? -1 : $today->diffInDays($gotDate);
        $message = $days > 0 ? "Winter Is Coming! {$cold} Faltan {$days} días!" : "Winter is Here!!!!";
        $this->espinoso->sendToCactus($message);
     }
}
