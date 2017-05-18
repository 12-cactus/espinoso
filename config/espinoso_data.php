<?php 
use \App\Espinoso\Helpers\Msg;

$suma = function ($pattern, $updates) {
    preg_match($pattern, $updates->message->text, $matches);
    $num1 = $matches[1]; 
    $num2 = $matches[2];
    return $num1 + $num2 ; 
};

$funAsentirRand = function ($pattern, $update) {
    $respuestas = ['claro que si', 'exactamente', 'mas vale'];
    $elegida = $respuestas[ array_rand($respuestas) ]; 
    return $elegida . ", " . $update->message->from->first_name ; 
};

$funDespedirseRand = function ($pattern, $update) {
    $respuestas = ['Chau!', 'Nos vemos', 'Aloha', 'Nos re vimos!','Saludame a tu jermu', 'Chupala puto'];
    $elegida = $respuestas[ array_rand($respuestas) ]; 
    return $elegida . ", " . $update->message->from->first_name ; 
};

function completeWordRegex($word) 
{
    return "^(?:.*[^a-z])?" . $word . "[^a-z]?";
}

$rbmMappings =  [
    '/macri.?$/i'    => 'Gato',
    '/^espinoso[^\?]?$/i' => 'Otra vez rompiendo los huevos... Que pija quieren?',
    '/' . completeWordRegex('asado') . '$/i'    => "Que se yo! Estos pibes son re caretas... Yo me voy a escabiar a Bernal, ahí se ven soquetes!",
    '/no[, ]* espinoso?/i' => Msg::plain($funAsentirRand),
    '/' . completeWordRegex('alan') . '$/i'     => [ 'Alan lo hace por dinero', 'acaso dijiste $$$ oriented programming?', ] ,
    '/' . completeWordRegex('marcos') . '$/i'   => '¿Quisiste decir Markos?',
    '/maximo.?$/i'   => 'Para programarme no usaron ni un solo if ;)',
    '/' . completeWordRegex('facu(?:ndo)?') . '$/i'     => 'Facu, ese es terrible puto',
    '/' . completeWordRegex('dan') . '$/i'      => 'dan, ese tiene tatuado pattern matching en el culo!',
    '/' . completeWordRegex('ines?') . '$/i'  => [ 'esa Ines esa una babosa, siempre mirando abs' , 'Ine es una niñita sensible e inocente!', 'Ine te deja sin pilas' ],
    '/(j+a+){5,}/i'  => 'ajajajajajaja, que plato!',
    '/fu[u]*ck/i'    => 'tranquilo vieja, todo va a salir bien.',
    '/mamu/i'        => 'papu',
    '/jarvis/i'        => 'Es un re puto! Aguante yo! La concha de tu madre all boys',
    '/hola.*espinoso/i'        => 'Que pija queres?',
    '/chau.*espinoso/i'        => Msg::plain($funDespedirseRand),
    '/papu/i'        => 'mamu',
    '/ponerla/i'     => 'bash: ponerla: command not found',
    '/contrato/i'    => 'el diccionario lo define como un acuerdo legal que no se puede romper, que no se puede romper...',
    '/maldicio[o]*n/i' => 'tranquilo vieja, todo va a salir bien.',
    '/concha.*lora/i'  => 'no eh, cuidame la boquita.',
    '/(dan.*tip)|(tip.*dan)/i'          => 'dan, no quiero asustarte pero sin TIP no hay titulo.. hace el TIP MIERDA!',
    '/(espinoso.*como.*(andas|estas))|(como.*(andas|estas).*espinoso)\??$/i'         =>  Msg::md([ 'He tenido dias mejores..', 'de lujo' , 'que carajo te importa?', 'comela puto' ]) ,
    '/espinoso.*pensas.*smalltalk\??$/i'   => Msg::md([ 'Amo su pureza..', '`MNU`' ]) ,
    '/([0-9][0-9]*)[ ]*\+[ ]*([0-9][0-9]*)/' => Msg::md($suma),
    '/empanada/i' => 'mmmm de carne y bien jugosa',

    '/^ayuda gsm$/i' => "gsm  [-z:ZOOM -s:SIZE -c:COLOR -t:MAPTYPE ] dirección.\nZOOM es un entero del 1-20\nSIZE es una resolución (600x300)\nMAPTYPE es un tipo de mapa de google, por defecto roadmap\n",

];

$rbmMappings['/espinoso.*claves.*/i'] = "Reconozco todas estas: \n" . implode("\n", array_keys($rbmMappings));


return [
    'ResponseByMatch' => [ 'mappings' => $rbmMappings, 
                           'ignore_names' => ['facu', 'ine'],
                         ], 
]; 
