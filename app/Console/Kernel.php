<?php

namespace App\Console;

use Carbon\Carbon;
use App\Console\Commands\HiCommand;
use App\Console\Commands\SetWebhook;
use App\Console\Commands\ScheduleCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        HiCommand::class,
        SetWebhook::class,
        ScheduleCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Temporal para no pisar el dracarys de GOT
        $schedule->command('espi:hi')
            ->weekdays()
            ->saturdays()
            ->twiceDaily(13, 22);
        $schedule->command('espi:hi')->sundays()->at('13:00');

        $schedule->command('espi:dracarys')
            ->sundays()
            ->at('22:00')
            ->between(Carbon::create(2019, 4, 5), Carbon::create(2019, 5, 20));

        $schedule->command('espi:agenda')->weekdays()->at('8:00');
        $schedule->command('espi:agenda')->weekends()->at('10:00');
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
