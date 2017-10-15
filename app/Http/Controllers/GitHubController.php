<?php namespace App\Http\Controllers;

use Carbon\Carbon;
use Spatie\Emoji\Emoji;
use App\Espinoso\Espinoso;
use App\Facades\GuzzleClient;
use Telegram\Bot\Objects\Message;
use Telegram\Bot\Api as ApiTelegram;
use Unisharp\Setting\SettingFacade as Setting;
use App\Espinoso\DeliveryServices\TelegramDelivery;

class GitHubController extends Controller
{
    /**
     * This method is a hook for github to catch & handle last commit.
     *
     * @param TelegramDelivery|ApiTelegram $telegram
     * @param Espinoso $espinoso
     */
    public function commitsWebhook(TelegramDelivery $telegram, Espinoso $espinoso)
    {
        $espinoso->setDelivery($telegram);
        $lastEvent = Setting::get('github_last_event');
        $response = GuzzleClient::get(config('github.events'))->getBody()->getContents();

        publish($response, 'log.json');
        collect(json_decode($response))
            ->filter($this->newestPushes($lastEvent))
            ->sortBy($this->creation())
            ->each($this->sendMessage($espinoso));
    }

    /*
     * Internals
     */

    /**
     * @param mixed $message
     * @return bool
     */
    protected function isTextMessage($message): bool
    {
        return $message !== null && $message->has('text');
    }

    /**
     * @param mixed $message
     * @return bool
     */
    protected function isNotTextMessage($message): bool
    {
        return !$this->isTextMessage($message);
    }

    /**
     * @param string $text
     * @return string
     */
    protected function parseCommand(string $text)
    {
        preg_match('/^\/([^\s@]+)@?(\S+)?\s?(.*)$/', $text, $matches);

        return isset($matches[1]) ? trim($matches[1]) : '';
    }

    /**
     * @param string $command
     * @param Message $message
     * @return string
     */
    protected function parseCommandAsKeyword(string $command, Message $message): string
    {
        return str_replace("/{$command}", "espi {$command}", $message->getText());
    }

    /**
     * @param $lastEvent
     * @return \Closure
     */
    protected function newestPushes($lastEvent): \Closure
    {
        return function ($event) use ($lastEvent) {
            return Carbon::parse($event->created_at) > Carbon::parse($lastEvent)
                && $event->type === 'PushEvent';
        };
    }

    /**
     * @return \Closure
     */
    protected function creation(): \Closure
    {
        return function ($event) {
            return Carbon::parse($event->created_at);
        };
    }

    /**
     * @param Espinoso $espinoso
     * @return \Closure
     */
    protected function sendMessage(Espinoso $espinoso): \Closure
    {
        return function ($event) use ($espinoso) {
            $emoji1 = Emoji::cactus();
            $emoji2 = Emoji::smirkingFace();
            $branch = str_replace('refs/heads/', '', $event->payload->ref);
            $user = $event->actor->display_login ?? $event->actor->login ?? 'anonymous';

            $commits = collect($event->payload->commits)->map(function ($commit) use ($event) {
                $link = config('github.url.commit') . $commit->sha;
                $sha = str_limit($commit->sha, 7, '');
                return "[Commit {$sha}]({$link}) _{$commit->message}_";
            })->implode("\n");

            $espinoso->sendMessage(
                config('espinoso.chat.dev'),
                trans('messages.new-event', compact('branch', 'user', 'emoji1', 'emoji2', 'commits'))
            );

            Setting::set('github_last_event', $event->created_at);
        };
    }
}
