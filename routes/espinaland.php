<?php

use Illuminate\Support\Facades\Route;
use Espinaland\Support\Facades\ReplyResponses;

Route::put('cool', function () {
    return ReplyResponses::text('cool, men!');
});
