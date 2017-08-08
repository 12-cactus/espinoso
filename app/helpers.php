<?php

function completeWordRegex($word)
{
    return "^(?:.*[^a-z])?" . $word . "[^a-z]?";
}

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
