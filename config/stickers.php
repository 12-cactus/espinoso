<?php

use App\Espinoso\Sticker;

return [

    'patterns' => [
        [
            'userId' => 350079781,
            'pattern' => ".*\bmaybe\b.*",
            'sticker' => Sticker::facuMaybe()
        ],
        [
            'userId' => 305359996,
            'pattern' => ".*\bloca\b.*|.*\bsra\b.*|.*\bkaka\b.*|.*\bkk\b.*|.*\bcristi.*|.*\bkirchner.*",
            'sticker' => Sticker::cfk()
        ],
    ],
];
