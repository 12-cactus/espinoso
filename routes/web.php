<?php

Route::post('/handle-update', ['uses' => 'TelegramController@handleUpdates']);

Route::post('/set-webhook', ['uses' => 'TelegramController@setWebhook']);

Route::post('/github-webhook', ['uses' => 'TelegramController@githubWebhook']);

// Route::get('/show-fucking-errors', ['uses' => 'TelegramController@freakingErrors']);