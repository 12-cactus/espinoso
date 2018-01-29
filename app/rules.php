<?php

use App\Espinaland\Support\Facades\Rule;

Rule::match('espi hi', 'GreetingManager@sayHi');
Rule::match('espi test {text}', 'GreetingManager@sayHi');
//Rule::match('espi #{tagName} {content}', 'TagsManager@set');
//Rule::prefix('espi')->group(function () {
//    Rule::match('#{tagName} {content}', 'TagsManager@setContent');
//    Rule::regex("#(?'tagName'\w+)\s*", 'TagsManager@getContent');
//});
//Rule::match('send me nudes', function (): OutputMessage {
//    return replyImage([
//        'url' => 'https://cdn...FErsE1a6t7-8.png',
//        'caption' => 'Acá tenés tu nude, hijo de puta!'
//    ]);
//});

// dump(resolve('rules'));
