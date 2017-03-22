<?php

Route::post('/handle-update', ['uses' => 'TelegramController@handleUpdates']);

Route::post('/set-webhook', ['uses' => 'TelegramController@setWebhook']);

Route::get('/show-fucking-errors', ['uses' => 'TelegramController@freakingErrors']);