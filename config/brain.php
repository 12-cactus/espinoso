<?php

return [

    'ignore_to' => [],

    /*
    |--------------------------------------------------------------------------
    | Brain Matches
    |--------------------------------------------------------------------------
    |
    | The following are matches used by \App\Espinoso\Handlers\BrainHandler
    |
    */

    'patterns' => [

        '/^macri\W*$/i' => [
            'reply' => 'Gato',
        ],

        '/^(espi(noso)?\s*){1,3}[i!\?\.]*$/i' => [
            'reply' => 'Otra vez rompiendo los huevos... Que pija quieren?',
        ],

        '/\b(asado)\b$/i'  => [
            'reply' => "Próxima juntada: sábado 15/7 en lo de Maru. Traigan bebidas. Canelones a la Maru. Para Facu un buen canelón de carne.",
        ],

        '/^(o\s+)?no(,)?\s+(espi(noso)?)(\?)+$/i' => [
            'reply' => [
                'Claro que si :name:!',
                'Exactamente :name:',
                'Mas vale :name:!'
            ]
        ],

        '/\b(alan)\b$/i' => [
            'reply' => [
                'Alan lo hace por dinero',
                'acaso dijiste $$$ oriented programming?',
                'no hagan calentar a Alan, que no está en condiciones de manosearse'
            ],
        ],

        '/\b(marcos)\b$/i' => [
            'reply' => '¿Quisiste decir Markos?',
        ],

        '/maximo\s?$/i' => [
            'reply' => 'Para programarme no usaron ni un solo if ;)',
        ],

        '/\b(facu(?:ndo)?)\b$/i' => [
            'reply' => 'Facu, ese es terrible puto',
        ],

        '/\b(ines?)\b$/i' => [
            'reply' => [
                'esa Ines esa una babosa, siempre mirando abs',
                'Ine es una niñita sensible e inocente!', 'Ine te deja sin pilas'
            ],
        ],

        '/(j+a+){5,}/i' => [
            'reply' => 'ajajajajajaja, que plato!',
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
            'reply' => 'Que pija queres?',
        ],

        '/^chau\s*(espi(noso)?)/i' => [
            'reply' => [
                'Chau :name:!',
                'Nos vemos :name:',
                'Aloha :name:',
                'Nos re vimos!',
                'Saludame a tu jermu, :name:',
                'Chupala puto',
            ],
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

        '/(dan.*tip)|(tip.*dan)/i' => [
            'reply' => 'dan, no quiero asustarte pero sin TIP no hay titulo.. hace el TIP MIERDA!',
        ],

        '/^(.)*(espi(noso)?)\s+(c(o|ó)mo)\s+(and(a|á)s|est(a|á)s)(\?)*$/i' => [
            'reply' => [
                'He tenido dias mejores..',
                'De lujo!!' ,
                'Qué carajo te importa?',
                'Qué carajo te importa, pelotudo!',
                'Comela puto',
                'Si te digo te miento...',
                'No me jodas :name:',
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

        // FIXME: this should be in a CommandHandler
        '/^ayuda gsm$/i' => [
            'reply' => "gsm [-z:ZOOM -s:SIZE -c:COLOR -t:MAPTYPE ] dirección.\nZOOM es un entero del 1-20\nSIZE es una resolución (600x300)\nMAPTYPE es un tipo de mapa de google, por defecto roadmap\n",
        ],
        
        '/^asadoodle$/i' => [
            'reply' => "https://beta.doodle.com/poll/4gq3rtbmqcutw37g"]
        ],
    ],
];
