<?php 
$rbmMappings =  [
    '/macri.?$/i'    => 'Gato',
    '/espinoso.?$/i' => 'Mande jefe!',
    '/marcos.?$/i'   => '¿Quisiste decir Markos?',
    '/maximo.?$/i'   => 'Para programarme no usaron ni un solo if ;)',
    '/facu.?$/i'     => 'Facu... ese tipo es terrible puto',
    '/dan.?$/i'      => 'dan, ese tiene tatuado pattern matching en el culo!',
    '/(j+a+){5,}/i'  => 'ajajajajajaja, que plato!',
    '/fu[u]*ck/i'    => 'tranquilo vieja, todo va a salir bien.',
    '/mamu/i'        => 'papu',
    '/papu/i'        => 'mamu',
    '/asado.?$/i'    => "El asado es el sábado 25/3 (noche) en el quincho de maru.\nLean y dami hacen el asado.\nAlvin hace la picada.\nLos demás traigan bebidas, putos!!",
    '/contrato/i'    => 'el diccionario lo define como un acuerdo legal que no se puede romper, que no se puede romper...',
    '/maldicio[o]*n/i' => 'tranquilo vieja, todo va a salir bien.',
    '/concha.*lora/i'  => 'no eh, cuidame la boquita.',
    '/(dan.*tip)|(tip.*dan)/i'          => 'dan, no quiero asustarte pero sin TIP no hay titulo.. hace el TIP MIERDA!',
    '/espinoso.*pensas.*smalltalk\??$/i'   => 'Amo su pureza..',
    '/espinoso.*como.*andas\??$/i'         =>  [ 'He tenido dias mejores..', 'de lujo' , 'que carajo te importa?' ] ,

    '/([0-9][0-9]*)[ ]*\+[ ]*([0-9][0-9]*)/' => function ($pattern, $updates) {
        preg_match($pattern, $updates->message->text, $matches);
        $num1 = $matches[1]; 
        $num2 = $matches[2];
        return $num1 + $num2 ; 
    }
];

$rbmMappings['/espinoso,.*claves.*?/i'] = "Reconozco todas estas: \n" . implode("\n", array_keys($rbmMappings));

return [
    'ResponseByMatch' => [ 'mappings' => $rbmMappings ], 
]; 
