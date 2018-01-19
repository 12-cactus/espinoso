<?php

use App\Lib\Sticker;

return [

    'patterns' => [
        [
            'userId' => 350079781,
            'pattern' => ".*\bmaybe\b.*",
            'sticker' => Sticker::FACUMAYBE
        ],
        [
            'userId' => 305359996,
            'pattern' => ".*\bloca\b.*|.*\bsra\b.*|.*\bkaka\b.*|.*\bkk\b.*|.*\bcristi.*|.*\bkirchner.*",
            'sticker' => Sticker::CFK
        ],
    ],
];
