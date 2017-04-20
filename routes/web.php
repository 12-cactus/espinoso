<?php

Route::post('/handle-update', ['uses' => 'TelegramController@handleUpdates']);

Route::post('/set-webhook', ['uses' => 'TelegramController@setWebhook']);

Route::post('/github-webhook', ['uses' => 'TelegramController@githubWebhook']);
