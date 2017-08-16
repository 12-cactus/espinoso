<?php

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
