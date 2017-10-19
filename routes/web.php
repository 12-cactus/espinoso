<?php

Route::get('/', function () {
    $response = Telegram::getMe();
    return "I'm @{$response->getUsername()}";
});

Route::post('/handle-update', ['uses' => 'TelegramController@handleUpdates']);

Route::post('/set-webhook', ['uses' => 'TelegramController@setWebhook']);

Route::post('/webhooks/github/commits', ['uses' => 'GitHubController@commitsWebhook']);
