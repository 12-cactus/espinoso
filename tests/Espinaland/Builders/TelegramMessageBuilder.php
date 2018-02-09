<?php

namespace Tests\Espinaland\Builders;
use App\Objects\Telegram\TelegramRequestMessage;
use Faker\Factory;
use Telegram\Bot\Objects\Message;

/**
 * Class TelegramMessageBuilder
 * @package Tests\Espinaland\Builders
 */
class TelegramMessageBuilder
{
    protected $params = [];

    public static function new()
    {
        return new TelegramMessageBuilder;
    }

    public function build()
    {
        $faker = Factory::create();

        $data = [
            'message_id' => $this->params['message_id'] ?? $faker->randomNumber(),
            'from' => [
                'id' => $this->params['from']['id'] ?? $faker->randomNumber(),
                'first_name' => $this->params['from']['first_name'] ?? 'John',
                'last_name'  => $this->params['from']['last_name']  ?? 'Doe',
                'username'   => $this->params['from']['username']   ?? 'JohnDoe'
            ],
            'chat' => [
                'id' => $this->params['chat']['id'] ?? $faker->randomNumber(),
                'first_name' => $this->params['chat']['first_name'] ?? 'John',
                'last_name'  => $this->params['chat']['last_name']  ?? 'Doe',
                'username'   => $this->params['chat']['username']   ?? 'JohnDoe',
                'type'       => $this->params['chat']['type'] ?? 'private'
            ],
            'date' => $this->params['date'] ?? 1459957719,
            'text' => $this->params['text'] ?? $faker->word
        ];

        return new TelegramRequestMessage(new Message($data));
    }

    public function text(string $text)
    {
        $this->params['text'] = $text;

        return $this;
    }
}