<?php namespace App\Espinoso\Handlers;

use App\Espinoso\Espinoso;
use Telegram\Bot\Objects\Message;
use Illuminate\Support\Collection;
use Unisharp\Setting\SettingFacade as Setting;

class SettingsHandler extends EspinosoCommandHandler
{
    /**
     * @var bool
     */
    protected $ignorePrefix = true;
    protected $patterns = [];
    protected $signature   = "[espi] get|set key [value]";
    protected $description = "Get-Set, yo solo quiero ser del Get-Set";

    public function __construct(Espinoso $espinoso)
    {
        parent::__construct($espinoso);

        $this->patterns = [
            [
                'name' => 'get',
                'pattern' => "(?'command'get)\s+(?'key'\w+)\s*$",
            ], [
                'name' => 'set',
                'pattern' => "(?'command'set)\s+(?'key'\w+)\s+(?'value'\w*)\s*$",
            ],
        ];

        $this->matches = collect($this->patterns)->mapWithKeys(function ($item) {
            return [$item['name'] => []];
        })->toArray();
    }

    /**
     * @var Collection|null
     */
    protected $match = null;

    public function shouldHandle(Message $message): bool
    {
        $this->message = $message;

        $this->match = collect($this->patterns)->filter(function ($item) {
            return $this->matchCommand($item['pattern'], $this->message, $this->matches[$item['name']]);
        });

        return $this->match->isNotEmpty();
    }

    public function handle(): void
    {
        $this->matches = collect($this->matches)->filter()->first();
        $method = "handle" . ucfirst(trim($this->matches['command']));
        $this->$method();
    }

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

    protected function handleSet(): void
    {
        $key   = $this->matches['key'];
        $chat  = $this->message->getChat()->getId();
        $value = $this->matches['value'];
        
        Setting::set("{$chat}.{$key}", $value);

        $this->espinoso->reply("Guardado! para traerlo usá _get_");
    }
}
