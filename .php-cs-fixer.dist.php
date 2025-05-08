<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude(['var', 'vendor'])
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,

        'yoda_style' => false,
        'concat_space' => ['spacing' => 'one'],
    ])
    ->setFinder($finder)
;
