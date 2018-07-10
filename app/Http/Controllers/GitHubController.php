<?php namespace App\Http\Controllers;

use Closure;
use Carbon\Carbon;
use Spatie\Emoji\Emoji;
use App\Espinoso;
use App\Facades\GuzzleClient;
use Telegram\Bot\Api as ApiTelegram;
use Unisharp\Setting\SettingFacade as Setting;
use Espinarys\Deliveries\TelegramDelivery;

class GitHubController extends Controller
{
    const SHA_LIMIT = 7;
    protected $allowedEvents = ['PushEvent'];

    /**
     * This method is a hook for github to catch & handle last commit.
     *
     * @param TelegramDelivery|ApiTelegram $telegram
     * @param Espinoso $espinoso
     */
    public function commitsWebhook(TelegramDelivery $telegram, Espinoso $espinoso)
    {
        $espinoso->setDelivery($telegram);
        $response = GuzzleClient::get(config('github.events'), [
            'auth' => [config('github.username'), config('github.token')]
        ])->getBody()->getContents();

        collect(json_decode($response))
            ->filter($this->newest())
            ->sortBy($this->creation())
            ->each($this->sendMessage($espinoso));
    }

    /*
     * Internals
     */

    /**
     * @return Closure
     */
    protected function newest(): Closure
    {
        return function ($event) {
            $lastEvent = Setting::get('github_last_event', Carbon::minValue());

            return in_array($event->type, $this->allowedEvents)
                && Carbon::parse($event->created_at) > Carbon::parse($lastEvent);
        };
    }

    /**
     * @return Closure
     */
    protected function creation(): Closure
    {
        return function ($event) {
            return Carbon::parse($event->created_at);
        };
    }

    /**
     * @param Espinoso $espinoso
     * @return Closure
     */
    protected function sendMessage(Espinoso $espinoso): Closure
    {
        return function ($event) use ($espinoso) {
            $emoji1 = Emoji::cactus();
            $emoji2 = Emoji::smirkingFace();
            $branch = str_replace('refs/heads/', '', $event->payload->ref);
            $user = $event->actor->display_login ?? $event->actor->login ?? 'anonymous';

            $commits = collect($event->payload->commits)->map(function ($commit) use ($event) {
                $link = config('github.commits') . $commit->sha;
                $sha  = str_limit($commit->sha, self::SHA_LIMIT, '');
                return "[{$sha}]({$link}) _{$commit->message}_";
            })->implode("\n");

            $espinoso->sendMessage(
                config('espinoso.chat.dev'),
                trans('messages.new-event', compact('branch', 'user', 'emoji1', 'emoji2', 'commits'))
            );

            Setting::set('github_last_event', $event->created_at);
        };
    }
}
