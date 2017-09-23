<?php namespace App\Espinoso\Handlers;

use Exception;
use App\Espinoso\Espinoso;
use Telegram\Bot\Objects\Message;
use Illuminate\Support\Facades\Log;

abstract class EspinosoHandler
{
    /**
     * @var Espinoso
     */
    protected $espinoso;
    /**
     * @var string
     */
    protected $signature;
    /**
     * @var string
     */
    protected $description;

    /**
     * EspinosoHandler constructor.
     * @param Espinoso $espinoso
     */
    public function __construct(Espinoso $espinoso)
    {
        $this->espinoso = $espinoso;
    }

    abstract public function handle(Message $message): void;
    abstract public function shouldHandle(Message $message): bool;

    /**
     * @return string
     */
    protected function help()
    {
        return empty($this->signature) ? '' : "*{$this->signature}*\n\t\t\t{$this->description}";
    }

    /**
     *
     */
    protected function replyNotFound()
    {
        $this->espinoso->reply('No encontré una mierda, che');
    }

    /**
     *
     */
    protected function replyError()
    {
        $this->espinoso->reply('Ups! Esta cosa anda como el culo...');
    }

    public function handleError(Exception $e, Message $message)
    {
        $clazz = get_called_class();
        Log::error($clazz);
        Log::error($message);
        Log::error($e);

        $error = "Fuck! Something blow up on `{$clazz}`:
- _{$e->getMessage()}_
- *Text:* {$message->getText()}

View Log for details";

        $this->espinoso->sendMessage(config('espinoso.chat.dev'), $error);
    }

    public function __toString()
    {
        return get_called_class();
    }


}