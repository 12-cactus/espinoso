<?php

return [

    'handlers' => [
        App\Espinoso\Handlers\StartCommandHandler::class,
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
        App\Espinoso\Handlers\GoogleSearchHandler::class,
        App\Espinoso\Handlers\TranslationHandler::class,
        App\Espinoso\Handlers\SettingsHandler::class,
        App\Espinoso\Handlers\TagsHandler::class,
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
