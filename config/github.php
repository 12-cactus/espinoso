<?php

$repo = env('GITHUB_REPO', '12-cactus/espinoso');

return [

    'username' => env('GITHUB_USER', 'espinoso12'),
    'token' => env('GITHUB_TOKEN', '123456'),
    'events' => "https://api.github.com/repos/{$repo}/events",
    'url' => [
        'commit' => "https://github.com/{$repo}/commit/"
    ]
];
