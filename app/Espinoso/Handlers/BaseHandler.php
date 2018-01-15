<?php namespace App\Espinoso\Handlers;

use Exception;
use Spatie\Emoji\Emoji;
use App\Espinoso\Espinoso;
use Telegram\Bot\Objects\Message;
use Illuminate\Support\Facades\Log;

abstract class BaseHandler
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
     * @var Message
     */
    protected $message;

    /**
     * EspinosoHandler constructor.
     * @param Espinoso $espinoso
     */
    public function __construct(Espinoso $espinoso)
    {
        $this->espinoso = $espinoso;
    }

    abstract public function handle(): void;
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
        $this->espinoso->reply($this->pickOne(trans('messages.not-found')));
    }

    /**
     *
     */
    protected function replyInMaintenance()
    {
        $this->espinoso->reply($this->pickOne(trans('messages.maintenance')));
    }

    /**
     *
     */
    protected function replyOk()
    {
        $this->espinoso->reply($this->pickOne(trans('messages.ok')));
    }

    /**
     *
     */
    protected function replyError()
    {
        $this->espinoso->reply($this->pickOne(trans('messages.error')));
    }

    protected function pickOne($messages)
    {
        $messages = collect($messages);

        $pos = mt_rand(0, $messages->count() - 1);

        return $messages->get($pos);
    }

    public function handleError(Exception $e, Message $message)
    {
        $clazz = get_called_class();
        Log::error($clazz);
        Log::error($message);
        Log::error($e);

        $scream = Emoji::faceScreamingInFear();
        $error = "{$scream} Fuck! Something blow up on `{$clazz}`
- *Error Message:* _{$e->getMessage()}_
- *Original Text:* {$message->getText()}

View Log for details";

        $this->espinoso->sendMessage(config('espinoso.chat.dev'), $error);
    }

    public function __toString()
    {
        return get_called_class();
    }
}
