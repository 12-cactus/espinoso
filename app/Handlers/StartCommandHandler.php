<?php

namespace App\Handlers;

use Illuminate\Support\Str;
use App\Facades\GoutteClient;

class StartCommandHandler extends BaseCommand
{
    /**
     * @var string
     */
    protected $pattern = "\b(start)\b(@[a-z]*)?\s*$";
    protected $ignorePrefix = true;

    public function handle(): void
    {
        $isNew = $this->espinoso->registerChat($this->message->getChat());

        if ($isNew) {
            $chat = $this->message->getChat();
            $this->espinoso->reply(trans('messages.chat.new', [
                'name' => $chat->getFirstName() ?? $chat->getTitle()
            ]));
            return;
        }

        $this->espinoso->reply(trans('messages.chat.new-again'));
    }
}
