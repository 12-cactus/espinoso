<?php

namespace App\Console\Commands;

use Carbon\Carbon;

class DeployedCommand extends EspiCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'espi:deployed {--git=} {--composer=} {--reload=} {--migrate=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manda mensaje luego de deployar en el server';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $datetime = Carbon::now();

        $message = "Me deployaron en el server a las {$datetime}. Pasaron estas cosas:\n";
        $message .= "git pull: {$this->textOption('git')}\n";
        $message .= "composer install: {$this->textOption('composer')}\n";
        $message .= "service reload: {$this->textOption('reload')}\n";
        $message .= "artisan migrate: {$this->textOption('migrate')}";

        $this->espinoso->sendToDev($message);
    }

    protected function textOption($option)
    {
        return $this->option($option) == 0 ? 'OK' : 'FAIL';
    }
}
