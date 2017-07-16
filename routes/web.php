<?php

Route::post('/handle-update', ['as' => 'update', 'uses' => 'TelegramController@handleUpdates']);

Route::post('/set-webhook', ['as' => 'set-webhook', 'uses' => 'TelegramController@setWebhook']);

Route::post('/github-webhook', ['uses' => 'TelegramController@githubWebhook']);
