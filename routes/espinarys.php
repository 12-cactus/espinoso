<?php

use Illuminate\Support\Facades\Route;

Route::put('cool', 'GreetingManager@cool');

Route::put('cool-named', 'GreetingManager@coolNamed');
