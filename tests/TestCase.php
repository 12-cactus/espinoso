<?php namespace Tests;

use Faker\Factory;
use Telegram\Bot\Objects\Message;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function text(string $text)
    {
        return $this->makeMessage(['text' => $text]);
    }

    // FIXME as Builder
    protected function makeMessage(array $params = [])
    {
        $faker = Factory::create();

        $data = [
            'message_id' => $params['message_id'] ?? $faker->randomNumber(),
            'from' => [
                'id' => $params['from']['id'] ?? $faker->randomNumber(),
                'first_name' => $params['from']['first_name'] ?? 'John',
                'last_name'  => $params['from']['last_name']  ?? 'Doe',
                'username'   => $params['from']['username']   ?? 'JohnDoe'
            ],
            'chat' => [
                'id' => $params['chat']['id'] ?? $faker->randomNumber(),
                'first_name' => $params['chat']['first_name'] ?? 'John',
                'last_name'  => $params['chat']['last_name']  ?? 'Doe',
                'username'   => $params['chat']['username']   ?? 'JohnDoe',
                'type'       => $params['chat']['type'] ?? 'private'
            ],
            'date' => $params['date'] ?? 1459957719,
            'text' => $params['text'] ?? $faker->word
        ];

        return new Message($data);
    }
}
