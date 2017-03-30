<?php
return [
    'handlers' => [ 
        App\Espinoso\Handlers\ResponseByMatch::class,
        App\Espinoso\Handlers\BardoDelEspinoso::class,
        App\Espinoso\Handlers\RandomInstagram::class,
        App\Espinoso\Handlers\GoogleStaticMaps::class,
    ],
];
