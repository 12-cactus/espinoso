<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SetWebhook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'espinoso:webhook-ngrok';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set ngrok publish services as webhook';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $route = "/set-webhook";
        $url = shell_exec('curl -s http://127.0.0.1:4040/status | grep -P "https://.*?ngrok.io" -oh');
        $url = trim($url) . $route;

        if ($url == $route) {
            $this->error('ngrok is not running...');
        } else {
            $data = shell_exec("wget --method=POST -q -O - {$url}");
            $data == '[true]' ? $this->info('Done!') : $this->error('something was wrong');
        }
    }
}
