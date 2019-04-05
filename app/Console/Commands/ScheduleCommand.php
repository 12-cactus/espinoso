<?php

namespace App\Console\Commands;

use Carbon\Carbon;

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
        $today = Carbon::today();

        collect(__('schedule.birthdays'))->filter(function ($event) use ($today) {
            return $event['day'] == $today->format('d/m');
        })->each(function ($event) {
            $this->espinoso->sendToCactus($event['message']);
        });

        // On PHP's date, the day of year starts at 0 (?)
        if ($today->dayOfYear + 1 === 256) {
            $this->espinoso->sendToCactus(__('schedule.programmerDayMessage'));
        }
        
        $this->gotDays();
    }
    
    protected function gotDays(): void
    {
        $days = $this->daysTo(2019, 4, 14);
        $message = $days > 0 ? "Winter Is Coming!! - {$days} días!!" : "Winter is Here!!!!";
        $this->espinoso->sendToCactus($message);
    }
    
    protected function daysTo($year, $month, $day)
    {
        $date = Carbon::create($year, $month, $day);
        return $date < now() ? -1 : now()->diffInDays($date);
    }
}
