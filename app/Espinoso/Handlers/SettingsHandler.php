<?php namespace App\Espinoso\Handlers;

use Unisharp\Setting\SettingFacade as Setting;

class SettingsHandler extends MultipleCommand
{
    /**
     * @var bool
     */
    protected $ignorePrefix = true;
    protected $patterns = [
        [
            'name' => 'get',
            'pattern' => "(?'command'get)\s+(?'key'\w+)\s*$",
        ], [
            'name' => 'set',
            'pattern' => "(?'command'set)\s+(?'key'\w+)\s+(?'value'.+)$",
        ],
    ];
    protected $signature   = "[espi] get|set key [value]";
    protected $description = "Get-Set, yo solo quiero ser del Get-Set";

    /**
     * Handle message when match with 'espi get <key>'
     */
    protected function handleGet(): void
    {
        $key   = $this->matches['key'];
        $chat  = $this->message->getChat()->getId();
        $value = Setting::get("{$chat}.{$key}");

        if (empty($value)) {
            $this->replyNotFound();
            return;
        }

        $this->espinoso->reply($value);
    }

    /**
     * Handle message when match with 'espi set <key> <value>'
     */
    protected function handleSet(): void
    {
        $key   = $this->matches['key'];
        $chat  = $this->message->getChat()->getId();
        $value = trim($this->matches['value']);

        Setting::set("{$chat}.{$key}", $value);

        $this->espinoso->reply(trans('messages.settings.saved'));
    }
}
