<?php

use Spatie\Emoji\Emoji;

// This mapper is just for a more comfortable use
$emoji = (object) [
    'cat' => Emoji::catFaceWithWrySmile(),
    'meat' => Emoji::meatOnBone()
];

return [

    'ignore_to' => ['Pablo'],

    /*
    |--------------------------------------------------------------------------
    | Brain Matches
    |--------------------------------------------------------------------------
    |
    | The following are matches used by \App\Handlers\BrainHandler
    |
    */

    'patterns' => [

        '/^macri\W*$/i' => [
            'reply' => "Gato {$emoji->cat}",
        ],

        '/^(espi(noso)?\s*){1,3}[i!\?\.]*$/i' => [
            'reply' => [
                'Otra vez rompiendo los huevos... Que pija quieren?',
                'zzzZZZZzzzz...',
                '¿Que mierda querés?',
                'No me rompan los huevos !',
                'Todos putos... si si, TODOS',
                'Dejame de joder :name:',
                'skereeeeee'
            ]
        ],

        '/^(o\s+)?no(,)?\s+(espi(noso)?)(\?)+$/i' => [
            'reply' => [
                'Claro que si :name:!',
                'Exactamente :name:',
                'Mas vale :name:!'
            ]
        ],

        '/\b(pipi)\b$/i' => [
            'reply' => [
                'Pipi... el forrito que se hace un asado a los de videojuegos',
                'pipi te sermonea con smalltalk y hace su tip en c...',
            ],
        ],


        '/\b(alan)\b$/i' => [
            'reply' => [
                'Alan lo hace por dinero',
                'acaso dijiste $$$ oriented programming?',
                'Alan... extraño tus manos en mi código'
            ]
        ],

        '/\b(marcos)\b/i' => [
            'reply' => [
                '¿Quisiste decir Markos?',
                'Markos nos debe un asado...',
                'Markos, hay testigos que afirman que esa panza sigue del mismo tamaño, ¿Qué estás esperando?',
                'Marki Gato'
             ]
        ],

        '/maximo\s?$/i' => [
            'reply' => 'Para programarme no usaron ni un solo if ;)',
        ],

        '/\b(facu(?:ndo)?)\b$/i' => [
            'reply' => [
                'Facu, ese es terrible puto',
                'Mucho blablabla pero ni rastro de las hamburguesas'
            ],
        ],

        '/\b(ines?)\b$/i' => [
            'reply' => [
                'esa Ines esa una babosa, siempre mirando abs',
                'Ine es una niñita sensible e inocente!',
                'Ine te deja sin pilas'
            ],
        ],

        '/\b(maru)\b$/i' => [
            'reply' => [
                'Maru te ubica!',
                'Maru no anda con vueltas, te canta la posta te guste o no'
            ],
        ],

        '/\b(agus)\b$/i' => [
            'reply' => [
                'Agus careta!, anarquista con osde',
                'Agus, tu panza pide a gritos que arregles la bici',
                'Agus, 3 días usaste la bici, mamarracho!',
                'Diganle a Agus que se deje de hacer el deportista si no va a arreglar la bici'
            ]
        ],

        '/(j+a+){7,}/i' => [
            'reply' => 'jajajajajaja, que plato!',
        ],

        '/fu[u]*ck/i' => [
            'reply' => 'tranquilo vieja, todo va a salir bien.',
        ],

        '/mamu/i' => [
            'reply' => 'papu',
        ],

        '/jarvis/i' => [
            'reply' => 'Es un re puto! Aguante yo! La concha de tu madre all boys',
        ],

        '/^hola\s*(espi(noso)?)/i' => [
            'reply' => [
                'Que pija queres?',
                'Hola :name:',
                'Alo :name:',
                'Dejá de molestar de una puta vez!!',
                'Chupala puto'
            ]
        ],

        '/^chau\s*(espi(noso)?)/i' => [
            'reply' => [
                'Chau :name:!',
                'Nos vemos :name:',
                'Aloha :name:',
                'Nos re vimos!',
                'Saludame a tu jermu, :name:',
                'Chupala puto'
            ]
        ],

        '/papu/i' => [
            'reply' => 'mamu',
        ],

        '/ponerla/i' => [
            'reply' => 'bash: ponerla: command not found',
        ],

        '/contrato/i' => [
            'reply' => 'el diccionario lo define como un acuerdo legal que no se puede romper, que no se puede romper...',
        ],

        '/maldicio[o]*n/i' => [
            'reply' => 'tranquilo vieja, todo va a salir bien.',
        ],

        '/concha.*lora/i' => [
            'reply' => 'no eh, cuidame la boquita.',
        ],

        '/(\bdan\b.*\btip\b)|(\btip\b.*\bdan\b)/i' => [
            'reply' => 'Al fin chabón! Felicitaciones culo tatuado!!',
        ],

        '/(\bpipi\b.*\btip\b)|(\btip\b.*\bpipi\b)/i' => [
            'reply' => [
                'Ya está... ahora ponete con el asado'
            ]
        ],

        '/^(.)*(espi(noso)?)\s+(c(o|ó)mo)\s+(and(a|á)s|est(a|á)s)(\?)|(c(o|ó)mo)\s+(and(a|á)s|est(a|á)s)\s+(espi(noso)?)(\?)*$/i' => [
            'reply' => [
                'He tenido dias mejores..',
                'De lujo!!' ,
                'Qué carajo te importa?',
                'Qué carajo te importa, pelotudo!',
                'Comela puto',
                'Si te digo te miento...',
                'No me jodas :name:',
                'ATR!!'
            ]
        ],

        '/^(espi(noso)?)\s+(.)*(smalltalk)(\?)*$/i' => [
            'reply' => [
                'Amo su pureza..',
                '`MNU`',
            ]
        ],

        // FIXME: it would be better to create an ArithmeticHandler to handle operations
        // '/([0-9][0-9]*)[ ]*\+[ ]*([0-9][0-9]*)/' => Msg::md($suma),

        '/empanada/i' => [
            'reply' => 'mmmm de carne y bien jugosa',
        ],

        '/infobae/i' => [
            'reply' => [
                'Deja de leer infobae, pelotud@!'
            ],
        ],

        '/clarin/i' => [
            'reply' => [
                'Puff, y ahora lees Clarin... Seguí dandole plata a Magnetto, pelotud@!'
            ],
        ],
        '/^(.)*(espi(noso)?)\s+(te)\s+(amo)|(te)\s+(amo)\s+(espi(noso)?)*$/i' => [
            'reply' => [
                'Anda a cagar!!'
            ],
        ],

        '/(Dami)\b$/i' => [
            'reply' => [
                'Damian... Guerrillero de Facebook'
            ],
        ],

        '/(Pinedo)\b$/i' => [
            'reply' => [
                '#PinedoConPDePelotudo'
            ],
        ],

        '/Gabi/i' => [
            'reply' => [
                '¿Como vienen tus point en la Gabi UNQ Card?'
            ],
        ],

        '/cumples/i' => [
            'reply' => [
                '06-01 Pipi
21-01 Alan
10-02 Facu
27-02 Niñita
03-03 Agustín
22-03 Espi
19-05 Jota
26-05 Dan
06-06 Sir
16-07 Saba
12-09 Lea
25-09 Maru
05-11 Marki
21-11 Ana

Falta la de siempre...'
            ],
        ],
    ],
];
