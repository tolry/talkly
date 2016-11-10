<?php

$finder = Symfony\CS\Finder\DefaultFinder::create()
    ->in(array(__DIR__ . '/src', __DIR__ . '/tests'))
;

return Symfony\CS\Config\Config::create()
    ->level(Symfony\CS\FixerInterface::PSR2_LEVEL)
    ->fixers(array(
        'header_comment',
        'newline_after_open_tag',
        'ordered_use',
        'short_array_syntax',
        'php_unit_construct',
    ))
    ->setUsingCache(true)
    ->finder($finder)
    ;