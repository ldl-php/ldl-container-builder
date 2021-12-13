<?php

declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';

use LDL\DependencyInjection\CompilerPass\Compiler\CompilerPassCompiler;
use LDL\DependencyInjection\CompilerPass\Finder\CompilerPassFileFinder;
use LDL\DependencyInjection\CompilerPass\Finder\Options\CompilerPassFileFinderOptions;
use LDL\Framework\Base\Collection\CallableCollection;
use Symfony\Component\DependencyInjection\ContainerBuilder;

echo "This example finds compiler passes and compiles them\n\n";

$compiler = new CompilerPassCompiler();

$finder = new CompilerPassFileFinder(
    CompilerPassFileFinderOptions::fromArray([
        'directories' => [__DIR__.'/Build'],
    ]),
    new CallableCollection([
        static function ($f) {
            echo "Found compiler pass $f ...\n";
        },
    ])
);

$passes = $finder->find();

echo "\nCompiling found compiler passes, no exception must be thrown ...\n\n";

$compiler->compile(new ContainerBuilder(), $passes);
