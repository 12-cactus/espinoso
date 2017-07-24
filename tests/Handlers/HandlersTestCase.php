<?php namespace Tests\Handlers;

use App\Espinoso\Handlers\EspinosoHandler;
use Faker\Factory;
use Tests\TestCase;

abstract class HandlersTestCase extends TestCase
{
    /**
     * @var EspinosoHandler
     */
    protected $handler;

    protected function update($params)
    {
        // FIXME as Builder
        $faker = Factory::create();

        $updates = [
            'update_id' => $params['update_id'] ?? $faker->randomNumber(),
            'message'   => [
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
            ]
        ];

        return json_decode(json_encode($updates));
    }

    protected function assertShouldHandle($handler, $message)
    {
        $this->assertTrue($handler->shouldHandle($this->update(['text' => $message])));
    }

    protected function assertShouldNotHandle($handler, $message)
    {
        $this->assertFalse($handler->shouldHandle($this->update(['text' => $message])));
    }
}
