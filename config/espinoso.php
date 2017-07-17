<?php
return [
    'handlers' => [ 
        App\Espinoso\Handlers\GitHubHandler::class,
        App\Espinoso\Handlers\CinemaHandler::class,
        App\Espinoso\Handlers\ResponseByMatch::class,
        App\Espinoso\Handlers\BardoDelEspinoso::class,
        App\Espinoso\Handlers\RandomInstagram::class,
        App\Espinoso\Handlers\GoogleStaticMaps::class,
        App\Espinoso\Handlers\Weather::class,
        App\Espinoso\Handlers\NextHolidayHandler::class,
        App\Espinoso\Handlers\ImdbHandler::class,
        App\Espinoso\Handlers\GoogleInfoBoxHandler::class,
    ],

    'github' => [
        'token' => env('GITHUB_TOKEN', '123')
    ]
];
