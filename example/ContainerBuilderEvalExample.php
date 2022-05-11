<?php

declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';

use LDL\DependencyInjection\CompilerPass\Compiler\CompilerPassCompiler;
use LDL\DependencyInjection\CompilerPass\Finder\CompilerPassFileFinder;
use LDL\DependencyInjection\CompilerPass\Finder\Options\CompilerPassFileFinderOptions;
use LDL\DependencyInjection\Container\Builder\LDLContainerBuilder;
use LDL\DependencyInjection\Container\Dumper\LDLContainerDumper;
use LDL\DependencyInjection\Service\Compiler\Directive\Collection\ServiceCompilerDirectiveCollection;
use LDL\DependencyInjection\Service\Compiler\Directive\DuplicateServiceCompilerDirective;
use LDL\DependencyInjection\Service\Compiler\Directive\Exception\DuplicateServiceIdException;
use LDL\DependencyInjection\Service\Compiler\ServiceCompiler;
use LDL\DependencyInjection\Service\File\Finder\Options\ServiceFileFinderOptions;
use LDL\DependencyInjection\Service\File\Finder\ServiceFileFinder;
use LDL\Framework\Base\Collection\CallableCollection;

echo "Try to build container, in the first build, an exception will be thrown since we add a compiler directive\n";
echo "which detects duplicate service id's\n\n";

$serviceFileFinder = new ServiceFileFinder(
    ServiceFileFinderOptions::fromArray([
        'directories' => [__DIR__.'/Build'],
    ]),
    new CallableCollection([
        static function ($f) {
            echo "Found service file $f\n";
        },
    ])
);

$compilerPassFinder = new CompilerPassFileFinder(
    CompilerPassFileFinderOptions::fromArray([
        'directories' => [__DIR__.'/Build'],
    ]),
    new CallableCollection([
        static function ($c) {
            echo "Found compiler pass: $c\n";
        },
    ])
);

try {
    $builder = new LDLContainerBuilder(
        new ServiceCompiler(
            new ServiceCompilerDirectiveCollection([
                new DuplicateServiceCompilerDirective(),
            ])
        ),
        new CompilerPassCompiler()
    );

    $container = $builder->build(
        $serviceFileFinder->find(),
        $compilerPassFinder->find()
    );
} catch (DuplicateServiceIdException $e) {
    echo "\n\nOK EXCEPTION: {$e->getMessage()})\n\n";
}

echo "Try to build container again, this time, without duplicate service id detection\n\n";

$builder = new LDLContainerBuilder(
    new ServiceCompiler(),
    new CompilerPassCompiler()
);

eval(LDLContainerDumper::dump(
    LDLContainerDumper::DUMP_FORMAT_PHP_EVAL,
    $builder->build(
        $serviceFileFinder->find(),
        $compilerPassFinder->find()
    )
));

$a = new \LDL\DependencyInjection\ServiceContainer();

echo "Evaled!\n";
