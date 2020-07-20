<?php
require_once 'vendor/autoload.php';
use \Dejurin\GoogleTranslateForFree;

echo getTranslation('Father');

function getTranslation(string $word): ?string
{
    $source = 'en';
    $target = 'ru';
    $attempts = 5;

    $tr = new GoogleTranslateForFree();
    $result = $tr->translate($source, $target, $word, $attempts);
    $result[0] = strtoupper($result[0]);

    return $result;
}