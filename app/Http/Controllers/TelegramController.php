<?php namespace App\Http\Controllers;

use App\Espinoso;
use Telegram\Bot\Objects\Update;
use Telegram\Bot\Objects\Message;
use Telegram\Bot\TelegramResponse;
use Telegram\Bot\Api as ApiTelegram;
use App\DeliveryServices\TelegramDelivery;
use Espinaland\Support\Facades\ThornyRoutes;
use Symfony\Component\HttpFoundation\Response;
use Espinaland\Interpreters\SimplifierCollection;

class TelegramController extends Controller
{
    /**
     * Main Telegram Reception
     *
     * @param TelegramDelivery $delivery
     * @param SimplifierCollection $simplifier
     * @return Response
     */
    public function newHandleUpdates(TelegramDelivery $delivery, SimplifierCollection $simplifier): Response
    {
        $message = $delivery->lastMessage();

        $routes = $simplifier->asRoutes($message->text());

        $responses = $routes->map(function (string $route) use ($message, $delivery) {
            return ThornyRoutes::handle($route, $message, 'telegram');
        });

        $all = $responses->none(function (Response $response) {
            return $response->getStatusCode() !== 200;
        });

        return response($all ? 'OK' : 'WARNING');
    }

    /**
     * Handle Telegram incoming message.
     *
     * @param TelegramDelivery $telegram
     * @param Espinoso $espinoso
     */
    public function handleUpdates(TelegramDelivery $telegram, Espinoso $espinoso): void
    {
        $espinoso->setDelivery($telegram);

        $update = $telegram->getUpdate();

        $this->handleUpdate($espinoso, $update);

        $this->handleMessage($espinoso, $update);

        $espinoso->checkIfHasRegisteredChat($update->getMessage()->getChat());
    }

    /**
     * Used for associate handleUpdates path as Telegram Webhook.
     * By default Telegram use its own hook to catch updates.
     *
     * @param ApiTelegram $telegram
     * @return TelegramResponse
     */
    public function setWebhook(ApiTelegram $telegram)
    {
        return $telegram->setWebhook(['url' => secure_url('new-handle-update')]);
    }

    /*
     * Internals
     */

    protected function handleUpdate(Espinoso $espinoso, Update $update): void
    {
        $newMember  = $update->getMessage()->get('new_chat_participant');
        $leftMember = $update->getMessage()->get('left_chat_participant');
        $chat = $update->getMessage()->getChat();

        if (!empty($newMember) && $espinoso->isMe($newMember)) {
            $espinoso->registerChat($chat);
            $espinoso->sendMessage($chat->getId(), trans('messages.chat.new', [
                'name' => $chat->getFirstName() ?? $chat->getTitle()
            ]));
        }

        if (!empty($leftMember) && $espinoso->isMe($leftMember)) {
            $espinoso->deleteChat($chat);
        }
    }

    protected function handleMessage(Espinoso $espinoso, Update $update)
    {
        $message = $update->getMessage();

        if ($this->isVoiceMessage($message)) {
            $text = trim($espinoso->transcribe($message));
            $espinoso->reply($text);
            $message->put('text', $text);
        }

        if ($this->isNotTextMessage($message)) {
            return;
        }

        $command = $this->parseCommand($message->getText());

        if (!empty($command)) {
            $message->put('text', $this->parseCommandAsKeyword($command, $message));
        }

        $espinoso->executeHandlers($message);
    }

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
    protected function isVoiceMessage($message): bool
    {
        return $message !== null && $message->has('voice');
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
    protected function parseCommand(string $text): string
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
}
