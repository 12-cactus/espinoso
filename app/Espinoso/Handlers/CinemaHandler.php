<?php namespace App\Espinoso\Handlers;

use Illuminate\Support\Str;
use App\Facades\GoutteClient;
use Telegram\Bot\Laravel\Facades\Telegram;

class CinemaHandler extends EspinosoCommandHandler
{
    public function shouldHandle($updates, $context = null)
    {
        return parent::shouldHandle($updates, $context)
            && $this->matchCommand('.*\bcine\b.*', $updates);
    }

    public function handle($updates, $context = null)
    {
        $crawler = GoutteClient::request('GET', config('espinoso.url.cinema'));

        $movies = [];
        $crawler->filter('.title > a')
            ->each(function ($node) use (&$movies) {
                $movies[] = Str::ucfirst(Str::lower($node->text()));
            });
        $movies = collect($movies)->map(function ($movie) {
            return " - {$movie}";
        })->implode("\n");

        $message = "¿La pensás poner?
¡Mete Netflix pelotud@, es mas barato!
Pero igual podes ver todas estas:\n
{$movies}";

        Telegram::sendMessage([
            'chat_id' => $updates->message->chat->id,
            'text'    => $message,
        ]);
    }
}
