<?php

namespace App\Console;

use App\Console\Commands\HiCommand;
use App\Console\Commands\SetWebhook;
use App\Console\Commands\ScheduleCommand;
use App\Console\Commands\GOTCountdownCommand;
use Carbon\Carbon;
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
        GOTCountdownCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('espi:hi')->twiceDaily(13, 22);
        $schedule->command('espi:got')
            ->dailyAt('10:14')
            ->between(Carbon::create(2019, 4, 5), Carbon::create(2019, 4, 17));
        $schedule->command('espi:dracarys')
            ->sundays()
            ->at('22:00')
            ->between(Carbon::create(2019, 4, 5), Carbon::create(2019, 5, 20));
        $schedule->command('espi:agenda')->dailyAt('10:00');
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
