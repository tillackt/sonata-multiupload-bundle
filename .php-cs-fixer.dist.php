<?php

$finder = PhpCsFixer\Finder::create()
    ->in('src')
    ->in('tests')
;

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        'ordered_class_elements' => true,
        'ordered_imports' => true,
        'no_unused_imports' => true,
        'psr_autoloading' => true,
        'array_syntax' => ['syntax' => 'short'],
    ])
    ->setFinder($finder)
;