<?php

return [

    'handlers' => [
        App\Handlers\StartCommandHandler::class,
        App\Handlers\HelpHandler::class,
        App\Handlers\GitHubHandler::class,
        App\Handlers\CinemaHandler::class,
        App\Handlers\BrainHandler::class,
        App\Handlers\BardoDelEspinosoHandler::class,
        App\Handlers\InstagramHandler::class,
        App\Handlers\GoogleStaticMapsHandler::class,
        App\Handlers\WeatherHandler::class,
        App\Handlers\NextHolidaysHandler::class,
        App\Handlers\IMDbHandler::class,
        App\Handlers\GoogleInfoBoxHandler::class,
        App\Handlers\StickersHandler::class,
        App\Handlers\GifsHandler::class,
        App\Handlers\GoogleSearchHandler::class,
        App\Handlers\TranslationHandler::class,
        App\Handlers\SettingsHandler::class,
        App\Handlers\TagsHandler::class,
        App\Handlers\HowMuchLeftHandler::class,
        App\Handlers\ServiceStatusSubwayHandler::class,
        App\Handlers\TrainHandler::class
    ],

    'chat' => [
        'dev' => env('TELEGRAM_DEVS_CHANNEL', 123),
        '12c' => env('TELEGRAM_12CACTUS_CHANNEL', env('TELEGRAM_DEVS_CHANNEL', 123)),
    ],

    'ssl-cert' => env('APP_SSL_CERT', false),

    'url' => [
        'info'   => 'https://www.google.com.ar/search?q=',
        'cinema' => 'https://www.themoviedb.org/movie/now-playing',
        'map'    => 'https://maps.googleapis.com/maps/api/staticmap',
        'holidays' => 'https://nolaborables.com.ar/api/v2/feriados/',
        'traductor' => 'http://translate.google.cn/translate_a/single',
        'themoviedb' => 'https://www.themoviedb.org',
        'subway' => 'https://rodrigoap.com/subte/api/subte.php',
        'train' => 'https://trency-api.now.sh/api/v1/'
    ],

    'voice' => [
        'url' => 'https://api.wit.ai/speech',
        'token' => env('VOICE_TOKEN', '123')
    ],

    'telegram' => [
        'url' => [
            'file' => 'https://api.telegram.org/file/bot'.env('TELEGRAM_BOT_TOKEN').'/'
        ]
    ],
/*
    'cinema' => [
        'search' => 'https://api.themoviedb.org/3/movie/76341?api_key='.env('THEMOVIEDB_TOKEN').'&query=',
        'language' =>
            'https://api.themoviedb.org/3/movie/76341?api_key='.env('THEMOVIEDB_TOKEN').'&language=es',
        'region' =>
            'https://api.themoviedb.org/3/search/movie?api_key='.env('THEMOVIEDB_TOKEN').'&query=whiplash&language=es-AR&region=AR'
    ]
*/
];
