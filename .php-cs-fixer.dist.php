<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude(['var', 'vendor'])
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
        '@PHP83Migration' => true,
        '@PHP80Migration:risky' => true,

        'array_syntax' => ['syntax' => 'short'],
        'ordered_imports' => ['sort_algorithm' => 'alpha'],
        'no_unused_imports' => true,
        'single_line_after_imports' => true,

        'declare_strict_types' => true,
        'ordered_class_elements' => [
            'order' => [
                'case',
                'use_trait',
                'constant_public',
                'constant_protected',
                'constant_private',
                'property_public',
                'property_protected',
                'property_private',
                'construct',
                'destruct',
                'phpunit',
                'method_public',
                'method_protected',
                'method_private',
            ],
        ],

        'yoda_style' => false,
        'concat_space' => ['spacing' => 'one'],

    ])
    ->setFinder($finder)
    ->setRiskyAllowed(true)
    ->setUsingCache(true)
;
