<?php 
$rbmMappings =  [
            '/macri/i' => 'Gato',
            '/marcos/i' => '¿Quisiste decir Markos?',
            '/maximo/i' => 'Para programarme no usaron ni un solo if ;)',
            '/facu/i'  => 'Facu... ese tipo es medio puto',
            '/dan/i'  => 'ese tiene tatuado pattern matching en el culo!',
        ];
$rbmMappings['/^claves?$/'] = implose(', ', array_keys($rbmMappings)),

return [
    'ResponseByMatch' => [ 'mappings' => $rbmMappings ], 

]; 