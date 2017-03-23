<?php 
$rbmMappings =  [
            '/macri/i' => 'Gato',
            '/marcos/i' => '¿Quisiste decir Markos?',
            '/maximo/i' => 'Para programarme no usaron ni un solo if ;)',
            '/facu/i'  => 'Facu... ese tipo es medio puto',
            '/dan/i'  => 'ese tiene tatuado pattern matching en el culo!',
            '/maldicio[o]*n/i'  => 'tranquilo vieja, todo va a salir bien.',
            '/fu[u]*ck/i'  => 'tranquilo vieja, todo va a salir bien.',
            '/concha.*lora/i'  => 'no eh, cuidame la boquita.',
            '/mamu/i'  => 'papu',
            '/papu/i'  => 'mamu',
            '/contrato/i'  => 'el diccionario lo define como un acuerdo legal que no se puede romper, que no se puede romper...',

        ];

$rbmMappings['/espinoso,.*claves.*?/i'] = implode(', ', array_keys($rbmMappings));

return [
    'ResponseByMatch' => [ 'mappings' => $rbmMappings ], 

]; 