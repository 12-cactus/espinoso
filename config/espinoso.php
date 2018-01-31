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
        App\Handlers\BirthdayHandler::class,
    ],

    'chat' => [
        'dev' => env('TELEGRAM_DEVS_CHANNEL', 123)
    ],

    'url' => [
        'info'   => 'https://www.google.com.ar/search?q=',
        'cinema' => 'http://www.hoyts.com.ar/ajaxCartelera.aspx?filter=Home&cine=&premium=False&_=1493929858090',
        'map'    => 'https://maps.googleapis.com/maps/api/staticmap',
        'holidays' => 'https://nolaborables.com.ar/api/v2/feriados/2018',
        'traductor' => 'http://translate.google.cn/translate_a/single',
    ],

    'voice' => [
        'url' => 'https://api.wit.ai/speech',
        'token' => env('VOICE_TOKEN', '123')
    ],

    'telegram' => [
        'url' => [
            'file' => 'https://api.telegram.org/file/bot'.env('TELEGRAM_BOT_TOKEN').'/'
        ]
    ]
];
