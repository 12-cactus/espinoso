<?php

namespace App;

use Exception;
use App\Facades\GuzzleClient;
use App\Handlers\BaseHandler;
use App\DeliveryServices\EspinosoDeliveryInterface;
use Psr\Http\Message\StreamInterface;
use Telegram\Bot\Objects\Chat;
use Telegram\Bot\Objects\Message;
use Telegram\Bot\Objects\User as UserObject;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Class Espinoso
 * @package Espinoso
 */
class Espinoso
{
    /**
     * @var array
     */
    protected $handlers;
    /**
     * @var EspinosoDeliveryInterface
     */
    protected $delivery;
    /**
     * @var Message
     */
    protected $message;

    public function __construct(Collection $handlers)
    {
        $this->handlers = $handlers->map(function ($handler) {
            return new $handler($this);
        });
    }

    /**
     * @param Message $message
     */
    public function executeHandlers(Message $message)
    {
        $this->message = $message;

        $this->getHandlers()->filter(function (BaseHandler $handler) {
            return $handler->shouldHandle($this->message);
        })->each(function (BaseHandler $handler) {
            try {
                $handler->handle($this->message);
            } catch (Exception $e) {
                $handler->handleError($e, $this->message);
            }
        });
    }

    /**
     * @param int $chatId
     * @param string $text
     * @param string $format
     * @param array $options
     */
    public function sendMessage(int $chatId, string $text, string $format = 'Markdown', array $options = []): void
    {
        $params = array_merge($options, [
            'chat_id' => $chatId,
            'text'    => $text,
            'parse_mode' => $format
        ]);

        $this->delivery->sendMessage($params);
    }

    /**
     * @param string $text
     * @param string $format
     * @param array $options
     */
    public function sendToCactus(string $text, string $format = 'Markdown', array $options = []): void
    {
        $this->sendMessage(config('espinoso.chat.12c'), $text, $format, $options);
    }

    /**
     * @param string $gif
     * @param string $format
     * @param array $options
     */
    public function sendGifToCactus(string $gif, string $format = 'Markdown', array $options = []): void
    {
        $params = array_merge($options, [
            'chat_id'  => config('espinoso.chat.12c'),
            'document' => $gif,
        ]);

        $this->delivery->sendGif($params);
    }

    /**
     * @param string $text
     * @param string $format
     * @param array $options
     */
    public function sendToDev(string $text, string $format = 'Markdown', array $options = []): void
    {
        $this->sendMessage(config('espinoso.chat.dev'), $text, $format, $options);
    }

    /**
     * @param string $text
     * @param string $format
     * @param array $options
     */
    public function reply(string $text, string $format = 'Markdown', array $options = []): void
    {
        $this->sendMessage($this->message->getChat()->getId(), $text, $format, $options);
    }

    public function replyDisablingPagePreview(string $text): void
    {
        $this->reply($text, 'Markdown', ['disable_web_page_preview' => true]);
    }

    /**
     * @param string $url
     * @param string $caption
     * @param array $options
     */
    public function replyImage(string $url, string $caption = '', array $options = []): void
    {
        $params = array_merge($options, [
            'chat_id' => $this->message->getChat()->getId(),
            'photo'   => $url,
            'caption' => $caption
        ]);

        $this->delivery->sendImage($params);
    }

    /**
     * @param string $sticker
     * @param array $options
     */
    public function replySticker(string $sticker, array $options = []): void
    {
        $params = array_merge($options, [
            'chat_id' => $this->message->getChat()->getId(),
            'sticker' => $sticker,
        ]);

        $this->delivery->sendSticker($params);
    }

    public function replyGif(string $gif, array $options = []): void
    {
        $params = array_merge($options, [
            'chat_id'  => $this->message->getChat()->getId(),
            'document' => $gif,
        ]);

        $this->delivery->sendGif($params);
    }

    /**
     * @param EspinosoDeliveryInterface $delivery
     */
    public function setDelivery(EspinosoDeliveryInterface $delivery)
    {
        $this->delivery = $delivery;
    }

    public function isMe(UserObject $user)
    {
        return $this->delivery->isMe($user);
    }

    /**
     * @return Collection
     */
    public function getHandlers(): Collection
    {
        return $this->handlers;
    }

    public function transcribe(Message $message)
    {
        $this->message = $message;
        $voice   = $message->getVoice();
        $fileId  = $voice->getFileId();
        $stream  = $this->delivery->getVoiceStream($voice);
        $chat    = $message->getChat();
        $audio   = $this->saveAndConvertAudio($fileId, $stream);

        try {
            // Get transcription
            $response = GuzzleClient::post(config('espinoso.voice.url'), [
                'headers' => [
                    'Authorization' => "Bearer " . config('espinoso.voice.token'),
                    'Content-Type' => 'audio/wav'
                ],
                'body' => $audio
            ]);

            $data = json_decode($response->getBody());

            return $data->_text;
        } catch (Exception $e) {
            Log::error($e);
            return trans('messages.not-transcribe', [
                'name' => $chat->getFirstName() ?? $chat->getTitle()
            ]);
        }
    }

    /**
     * @param Chat $chat
     * @return bool
     */
    public function registerChat(Chat $chat)
    {
        return $this->delivery->registerChat($chat);
    }

    /**
     * @param Chat $chat
     */
    public function deleteChat(Chat $chat): void
    {
        $this->delivery->deleteChat($chat);
    }

    public function checkIfHasRegisteredChat(Chat $chat): void
    {
        if (!$this->hasRegisteredChat($chat)) {
            $this->sendMessage($chat->getId(), trans('messages.chat.set-start'));
        }
    }

    protected function hasRegisteredChat(Chat $chat): bool
    {
        return $this->delivery->hasRegisteredChat($chat);
    }

    /**
     * @param string $fileId
     * @param StreamInterface $stream
     * @return mixed
     */
    protected function saveAndConvertAudio(string $fileId, StreamInterface $stream)
    {
        // Save as ogg (Telegram audio format)
        // and convert it to wav (Voice format required)
        Storage::put("{$fileId}.ogg", $stream->getContents());
        $fileIn = storage_path("app/{$fileId}.ogg");
        $fileOut = storage_path("app/{$fileId}.wav");
        @exec("ffmpeg -y -i {$fileIn} {$fileOut} 2> /dev/null");
        $audio = Storage::get("{$fileId}.wav");
        @exec("rm -f {$fileIn} 2> /dev/null");
        @exec("rm -f {$fileOut} 2> /dev/null");
        return $audio;
    }
}
