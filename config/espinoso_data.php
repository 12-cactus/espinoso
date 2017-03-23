<?php 
$rbmMappings =  [
            '/macri.?$/i'   => 'Gato',
            '/marcos.?$/i'  => '¿Quisiste decir Markos?',
            '/maximo.?$/i'  => 'Para programarme no usaron ni un solo if ;)',
            '/facu.?$/i'    => 'Facu... ese tipo es terrible puto',
            '/dan.?$/i'     => 'dan, ese tiene tatuado pattern matching en el culo!',
            '/(j+a+){3,}/i'   => 'ajajajajajaja, que plato!',
            '/fu[u]*ck/i'   => 'tranquilo vieja, todo va a salir bien.',
            '/mamu/i'       => 'papu',
            '/papu/i'       => 'mamu',
            '/contrato/i'   => 'el diccionario lo define como un acuerdo legal que no se puede romper, que no se puede romper...',
            '/maldicio[o]*n/i' => 'tranquilo vieja, todo va a salir bien.',
            '/concha.*lora/i'  => 'no eh, cuidame la boquita.',
            '/(dan.*tip)|(tip.*dan)/i' => 'dan, no quiero asustarte pero sin TIP no hay titulo.. hace el TIP MIERDA!',

        ];

$rbmMappings['/espinoso,.*claves.*?/i'] = "Reconozco todas estas: \n" . implode("\n", array_keys($rbmMappings));

return [
    'ResponseByMatch' => [ 'mappings' => $rbmMappings ], 

]; 
