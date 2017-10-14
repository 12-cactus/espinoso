<?php

$repo = env('GITHUB_REPO', '12-cactus/espinoso');

return [

    'events' => "https://api.github.com/repos/{$repo}/events",
    'url' => [
        'commit' => "https://github.com/{$repo}/commit/"
    ]
];
