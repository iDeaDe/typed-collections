<?php

$files = PhpCsFixer\Finder::create()
    ->in([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ]);

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12'                      => true,
        '@PER-CS2.0'                  => true,
        '@PER-CS2.0:risky'            => true,
        'array_syntax'                => [
            'syntax' => 'short',
        ],
        'trailing_comma_in_multiline' => true,
        'no_superfluous_phpdoc_tags'  => true,
        'global_namespace_import'     => [
            'import_classes'   => true,
            'import_constants' => true,
            'import_functions' => true,
        ],
        'blank_line_before_statement' => ['statements' => ['continue', 'return', 'try', 'throw']],
    ])
    ->setRiskyAllowed(true)
    ->setFinder($files);
