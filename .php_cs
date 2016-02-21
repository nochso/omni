<?php

use Symfony\CS\Config\Config;
use Symfony\CS\Finder\DefaultFinder;
use Symfony\CS\FixerInterface;

$gitIgnoreLines = array_map(function ($line) {
    return rtrim($line, "\r\n\\/");
}, file('.gitignore'));

$finder = DefaultFinder::create()
    ->in(__DIR__)
    ->exclude($gitIgnoreLines);

return Config::create()
    ->level(FixerInterface::SYMFONY_LEVEL)
    ->fixers([
        '-psr0', // Does not play well with PSR-4
        'concat_with_spaces',
        '-concat_without_spaces',
        '-empty_return',
        '-return',
        'short_array_syntax',
    ])
    ->finder($finder);
