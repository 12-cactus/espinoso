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

    public function handle(Message $message)
    {
        $data = $this->espinoso->getHandlers()->map(function ($handler) {
            return new $handler($this->espinoso, $this->telegram);
        })->map(function (EspinosoHandler $handler) {
            return $handler->help();
        })->reject(function (string $help) {
            return empty($help);
        })->implode("\n");

        return $this->telegram->sendMessage([
            'chat_id' => $message->getChat()->getId(),
            'text'    => "Entiendo masomenos estas cosas:\n\n{$data}",
            'parse_mode' => 'Markdown'
        ]);
    }
}
