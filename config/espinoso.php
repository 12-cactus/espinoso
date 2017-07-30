<?php
return [
    'handlers' => [ 
        App\Espinoso\Handlers\GitHubHandler::class,
        App\Espinoso\Handlers\CinemaHandler::class,
        App\Espinoso\Handlers\ResponseByMatch::class,
        App\Espinoso\Handlers\BardoDelEspinosoHandler::class,
        App\Espinoso\Handlers\RandomInstagram::class,
        App\Espinoso\Handlers\GoogleStaticMaps::class,
        App\Espinoso\Handlers\Weather::class,
        App\Espinoso\Handlers\NextHolidayHandler::class,
        App\Espinoso\Handlers\ImdbHandler::class,
        App\Espinoso\Handlers\GoogleInfoBoxHandler::class,
    ],

    'github' => [
        'token' => env('GITHUB_TOKEN', '123')
    ],

    'chat' => [
        'dev' => env('TELEGRAM_DEVS_CHANNEL', 123)
    ],

    'url' => [
        'issues' => 'https://api.github.com/repos/12-cactus/espinoso/issues',
        'cinema' => 'http://www.hoyts.com.ar/ajaxCartelera.aspx?filter=Home&cine=&premium=False&_=1493929858090'
    ]
];
