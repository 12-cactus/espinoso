<?php

use App\Model\TelegramChat;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/* @var Factory $factor */

$factory->define(TelegramChat::class, function (Faker $faker) {
    return [
        'id'   => $faker->randomNumber(),
        'type' => $faker->randomElement(['private', 'group', 'supergroup', 'channel']),
    ];
});


$factory->state(TelegramChat::class, 'private', function (Faker $faker) {
    return [
        'type' => 'private',
        'username' => $faker->userName,
        'first_name' => $faker->firstName
    ];
});
