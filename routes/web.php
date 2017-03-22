<?php

Route::post('/handle-update', ['uses' => 'TelegramController@handleUpdates']);

Route::post('/set-webhook', ['uses' => 'TelegramController@setWebhook']);

Route::post('/show-fucking-errors', ['uses' => 'TelegramController@freakingErrors']);