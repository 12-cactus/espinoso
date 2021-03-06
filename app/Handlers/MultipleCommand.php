<?php

namespace App\Handlers;

use App\Espinoso;
use Telegram\Bot\Objects\Message;

abstract class MultipleCommand extends BaseCommand
{
    /**
     * @var array
     *
     * You should override this $pattern attribute with your own patterns.
     * Each pattern entry should have a 'name' entry to match with handleName() method.
     */
    protected $patterns = [];

    public function __construct(Espinoso $espinoso)
    {
        parent::__construct($espinoso);

        // Define a $matches array with the same keys as $patterns.
        // This should be used to match each pattern in shouldHandle() method.
        $this->matches = collect($this->patterns)->mapWithKeys(function ($item) {
            return [$item['name'] => []];
        })->toArray();
    }

    public function shouldHandle(Message $message): bool
    {
        $this->message = $message;

        return collect($this->patterns)->filter(function ($item) {
            $match = $this->matchCommand($item['pattern'], $this->message, $this->matches[$item['name']]);
            if ($match) {
                $this->matches[$item['name']]['method'] = camel_case("handle-{$item['name']}");
            }
            return $match;
        })->isNotEmpty();
    }

    public function handle(): void
    {
        $this->matches = collect($this->matches)->filter()->first();

        $method = $this->matches['method'];

        $this->$method();
    }
}
