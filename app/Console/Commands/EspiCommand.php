<?php

namespace App\Console\Commands;

use App\Espinoso;
use App\DeliveryServices\TelegramDelivery;
use Illuminate\Console\Command;

abstract class EspiCommand extends Command
{
    protected $espinoso;

    /**
     * Create a new command instance.
     *
     * @param TelegramDelivery $telegram
     * @param Espinoso $espinoso
     */
    public function __construct(TelegramDelivery $telegram, Espinoso $espinoso)
    {
        parent::__construct();
        $this->espinoso = $espinoso;
        $this->espinoso->setDelivery($telegram);
    }
}
