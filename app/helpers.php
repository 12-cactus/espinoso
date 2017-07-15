<?php

function completeWordRegex($word)
{
    return "^(?:.*[^a-z])?" . $word . "[^a-z]?";
}