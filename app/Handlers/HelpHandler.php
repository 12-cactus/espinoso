<?php

namespace App\Handlers;

class HelpHandler extends BaseCommand
{
    /**
     * @var string
     */
    protected $pattern = "(ayuda|help|aiiiuuuda)(!)*";

    protected $signature   = "espi help|ayuda|aiiiuuda";
    protected $description = "muestra cosas que entiendo";

    public function handle(): void
    {
        $data = $this->espinoso->getHandlers()->map(function (BaseHandler $handler) {
            return $handler->help();
        })->reject(function (string $help) {
            return empty($help);
        })->sort(function ($firstText, $secondText) {
            return strcmp($this->removePrefix($firstText), $this->removePrefix($secondText));
        })->implode("\n");

        $this->espinoso->reply("Entiendo masomenos estas cosas:\n\n{$data}");
    }

    protected function removePrefix($text)
    {
        $text = str_replace('[espi] ', '', $text);

        return str_replace('espi ', '', $text);
    }
}
