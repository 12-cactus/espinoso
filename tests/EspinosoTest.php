<?php namespace Tests;

use App\Facades\Espinoso;
use App\Model\TelegramUser;


/**
 * Class EspinosoTest
 * @package Tests
 */
class EspinosoTest extends DBTestCase
{
    /**
     * @test
     *
     * @return void
     */
    public function it_store_user_data_when_receive_an_update()
    {
//        // Arrange
//        $update = $this->update([
//            'from' => [
//                'id' => 12345,
//                'first_name' => 'John',
//                'last_name'  => 'Doe',
//                'username'   => 'JohnDoe'
//            ]
//        ]);
//
//        // Act
//        Espinoso::register($update);
//        $user = TelegramUser::whereTelegramId(12345)->first();
//
//        // Assert
//        $this->assertEquals('John', $user->first_name);
//        $this->assertEquals('Doe', $user->last_name);
//        $this->assertEquals('JohnDoe', $user->username);
    }
}
