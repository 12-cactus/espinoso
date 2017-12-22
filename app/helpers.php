<?php

use Illuminate\Support\Facades\Storage;

/**
 * @param string $str
 * @return string
 */
function clean_string(string $str)
{
    $str = trim($str);

    // replace multiple spaces with a single space
    return strval(preg_replace('!\s+!', ' ', $str));
}

function publish($data, $file = 'log')
{
    Storage::disk('public')->put($file, $data);
}
