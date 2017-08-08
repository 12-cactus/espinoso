<?php namespace App\Espinoso\Handlers;

use Illuminate\Support\Str;
use App\Facades\GoutteClient;
use Telegram\Bot\Objects\Message;

class CinemaHandler extends EspinosoCommandHandler
{
    public function shouldHandle(Message $message): bool
    {
        return $this->matchCommand('.*\bcine\b.*', $message);
    }

    public function handle(Message $message)
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

        $response = "¿La pensás poner?
¡Mete Netflix pelotud@, es mas barato!
Pero igual podes ver todas estas:\n
{$movies}";

        $this->telegram->sendMessage([
            'chat_id' => $message->getChat()->getId(),
            'text'    => $response,
        ]);
    }
}
