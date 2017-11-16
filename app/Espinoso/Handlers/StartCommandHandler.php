<?php namespace App\Espinoso\Handlers;

use Illuminate\Support\Str;
use App\Facades\GoutteClient;

class StartCommandHandler extends EspinosoCommandHandler
{
    /**
     * @var string
     */
    protected $pattern = "\b(start)\b\s*$";
    protected $ignorePrefix = true;
    protected $signature    = "start";
    protected $description  = "es el comando que se ejecuta cuando iniciás un chat con espi o lo agregás a un grupo";

    public function handle(): void
    {
        $isNew = $this->espinoso->registerChat($this->message->getChat());

        if ($isNew) {
            $this->espinoso->reply(trans('messages.chat.new', [
                'name' => $this->message->getChat()->getFirstName()
            ]));
            return;
        }

        $this->espinoso->reply(trans('messages.chat.new-again'));
    }
}
