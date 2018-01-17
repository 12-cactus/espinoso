<?php

$repo = env('GITHUB_REPO', '12-cactus/espinoso');

return [

    'token' => env('GITHUB_TOKEN', '123456'),

    'username' => env('GITHUB_USER', 'espinoso12'),

    'events' => "https://api.github.com/repos/{$repo}/events",

    'commits' => "https://github.com/{$repo}/commit/",

    'issues' => "https://github.com/{$repo}/issues",

    'issues-api' => "https://api.github.com/repos/{$repo}/issues",
];
