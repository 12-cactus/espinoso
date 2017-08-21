<?php namespace App\Espinoso\Handlers;

use Telegram\Bot\Objects\Message;

class HelpHandler extends EspinosoCommandHandler
{
    /**
     * @var string
     */
    protected $pattern = "(ayuda|help|aiiiuuuda)(!)*";

    protected $signature   = "espi help|ayuda|aiiiuuda";
    protected $description = "muestra cosas que entiendo";

    public function handle(Message $message): void
    {
        $data = $this->espinoso->getHandlers()->map(function ($handler) {
            return new $handler($this->espinoso);
        })->map(function (EspinosoHandler $handler) {
            return $handler->help();
        })->reject(function (string $help) {
            return empty($help);
        })->implode("\n");

        $this->espinoso->reply("Entiendo masomenos estas cosas:\n\n{$data}");
    }
}
