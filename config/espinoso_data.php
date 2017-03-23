<?php 
$rbmMappings =  [
            '/macri/i' => 'Gato',
            '/marcos/i' => '¿Quisiste decir Markos?',
            '/maximo/i' => 'Para programarme no usaron ni un solo if ;)',
            '/facu/i'  => 'Facu... ese tipo es medio puto',
            '/dan/i'  => 'ese tiene tatuado pattern matching en el culo!',
            '/maldici[oó]n/i'  => 'tranquilo vieja, todo va a salir bien.',
        ];

$rbmMappings['/espinoso,[]*claves\?/i'] = implode(', ', array_keys($rbmMappings));

return [
    'ResponseByMatch' => [ 'mappings' => $rbmMappings ], 

]; 