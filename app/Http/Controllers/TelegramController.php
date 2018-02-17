<?php namespace App\Http\Controllers;

use App\Espinoso;
use App\Model\TelegramChat;
use Telegram\Bot\Objects\Chat;
use Telegram\Bot\Objects\Update;
use Telegram\Bot\Objects\Message;
use Telegram\Bot\TelegramResponse;
use Telegram\Bot\Api as ApiTelegram;
use Espinaland\Deliveries\TelegramDelivery;
use Espinaland\Support\Facades\ThornyRoutes;
use Symfony\Component\HttpFoundation\Response;
use Espinaland\Parsing\ThornyParsersCollection;

class TelegramController extends Controller
{
    /**
     * Main Telegram Reception
     *
     * @param TelegramDelivery $delivery
     * @param ThornyParsersCollection $parsers
     * @return Response
     */
    public function newHandleUpdates(TelegramDelivery $delivery, ThornyParsersCollection $parsers): Response
    {
        $message = $delivery->lastMessage();

        $routes = $parsers->asRoutes($message->text());

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
            $this->registerChat($chat);
            $espinoso->sendMessage($chat->getId(), trans('messages.chat.new', [
                'name' => $chat->getFirstName() ?? $chat->getTitle()
            ]));
        }

        if (!empty($leftMember) && $espinoso->isMe($leftMember)) {
            $this->deleteChat($chat);
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

    /**
     * Register chat and return true if new
     *
     * @param Chat $chat
     * @return bool
     */
    public function registerChat(Chat $chat): bool
    {
        /** @var TelegramChat $telegramChat */
        $telegramChat = TelegramChat::find($chat->getId());
        $isNew = empty($telegramChat);

        $telegramChat = $telegramChat ?? new TelegramChat;
        $telegramChat->id = $chat->getId();
        $telegramChat->type = $chat->getType();
        $telegramChat->title = $chat->getTitle();
        $telegramChat->username = $chat->getUsername();
        $telegramChat->first_name = $chat->getFirstName();
        $telegramChat->last_name = $chat->getLastName();
        $telegramChat->all_members_are_administrators = boolval($chat->get("all_members_are_administrators"));
        $telegramChat->photo = $chat->get("photo")->big_file_id ?? "";
        $telegramChat->description = $chat->get('description');
        $telegramChat->save();

        return $isNew;
    }

    /**
     * Delete chat
     *
     * @param Chat $chat
     */
    public function deleteChat(Chat $chat): void
    {
        $chat = TelegramChat::find($chat->getId());
        if (!empty($chat)) {
            $chat->delete();
        }
    }

    /**
     * @param Chat $chat
     * @return bool
     */
    public function hasRegisteredChat(Chat $chat): bool
    {
        $telegramChat = TelegramChat::find($chat->getId());
        return !empty($telegramChat);
    }
}
