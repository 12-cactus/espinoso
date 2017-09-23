<?php

return [
    'handlers' => [ 
        App\Espinoso\Handlers\HelpHandler::class,
        App\Espinoso\Handlers\GitHubHandler::class,
        App\Espinoso\Handlers\CinemaHandler::class,
        App\Espinoso\Handlers\BrainHandler::class,
        App\Espinoso\Handlers\BardoDelEspinosoHandler::class,
        App\Espinoso\Handlers\InstagramHandler::class,
        App\Espinoso\Handlers\GoogleStaticMapsHandler::class,
        App\Espinoso\Handlers\WeatherHandler::class,
        App\Espinoso\Handlers\NextHolidaysHandler::class,
        App\Espinoso\Handlers\IMDbHandler::class,
        App\Espinoso\Handlers\GoogleInfoBoxHandler::class,
        App\Espinoso\Handlers\StickersHandler::class,
        App\Espinoso\Handlers\GifsHandler::class,
        //App\Espinoso\Handlers\MemeHandler::class,
    ],

    'token' => [
        'github' => env('GITHUB_TOKEN', '123'),
    ],

    'chat' => [
        'dev' => env('TELEGRAM_DEVS_CHANNEL', 123)
    ],

    'url' => [
        'issues' => 'https://api.github.com/repos/12-cactus/espinoso/issues',
        'info'   => 'https://www.google.com.ar/search?q=',
        'cinema' => 'http://www.hoyts.com.ar/ajaxCartelera.aspx?filter=Home&cine=&premium=False&_=1493929858090',
        'map'    => 'https://maps.googleapis.com/maps/api/staticmap',
        'holidays' => 'http://www.elproximoferiado.com/',
    ]
];
