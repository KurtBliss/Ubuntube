<?php

$key = 0;

function incKey()
{
    global $key;
    $key += 1;
    return $key;
}

$html = <<<HTML
    <button id="button-${incKey()}$key">button</button>
    <button id="button-${incKey()}$key">button</button>
    <button id="button-${incKey()}$key">button</button>
    $key
HTML;

echo $html;