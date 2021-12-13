<?php

declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';

use LDL\DependencyInjection\CompilerPass\Compiler\CompilerPassCompiler;
use LDL\DependencyInjection\CompilerPass\Finder\CompilerPassFileFinder;
use LDL\DependencyInjection\CompilerPass\Finder\Options\CompilerPassFileFinderOptions;
use LDL\DependencyInjection\Container\Builder\LDLContainerBuilder;
use LDL\DependencyInjection\Service\Compiler\Directive\Collection\ServiceCompilerDirectiveCollection;
use LDL\DependencyInjection\Service\Compiler\Directive\DuplicateServiceCompilerDirective;
use LDL\DependencyInjection\Service\Compiler\Directive\Exception\DuplicateServiceIdException;
use LDL\DependencyInjection\Service\Compiler\ServiceCompiler;
use LDL\DependencyInjection\Service\File\Finder\Options\ServiceFileFinderOptions;
use LDL\DependencyInjection\Service\File\Finder\ServiceFileFinder;
use LDL\Framework\Base\Collection\CallableCollection;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;

echo "Try to build container, in the first build, an exception will be thrown since we add a compiler directive\n";
echo "which detects duplicate service id's\n\n";

try {
    $builder = new LDLContainerBuilder(
        new ServiceFileFinder(ServiceFileFinderOptions::fromArray([
            'directories' => [__DIR__.'/Build'],
        ])),
        new ServiceCompiler(
            new ServiceCompilerDirectiveCollection([
                new DuplicateServiceCompilerDirective(),
            ])
        ),
        new CompilerPassFileFinder(
            CompilerPassFileFinderOptions::fromArray([
                'directories' => [__DIR__.'/Build'],
            ])
        ),
        new CompilerPassCompiler()
    );

    $container = $builder->build();
} catch (DuplicateServiceIdException $e) {
    echo "OK EXCEPTION: {$e->getMessage()})";
}

echo "Try to build container again, this time, without duplicate service id detection\n\n";

$builder = new LDLContainerBuilder(
    new ServiceFileFinder(
        ServiceFileFinderOptions::fromArray([
            'directories' => [__DIR__.'/Build'],
        ]),
        new CallableCollection([
            static function ($f) {
                echo "Found service file $f\n";
            },
        ])
    ),
    new ServiceCompiler(),
    new CompilerPassFileFinder(
        CompilerPassFileFinderOptions::fromArray([
            'directories' => [__DIR__.'/Build'],
        ]),
        new CallableCollection([
            static function ($f) {
                echo "Found compiler pass file $f\n";
            },
        ])
    ),
    new CompilerPassCompiler()
);

$container = $builder->build();

$dumper = new PhpDumper($container);
echo $dumper->dump()."\n";
