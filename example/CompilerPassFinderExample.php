<?php

declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';

use LDL\DependencyInjection\CompilerPass\Finder\CompilerPassFileFinder;
use LDL\DependencyInjection\CompilerPass\Finder\Options\CompilerPassFileFinderOptions;
use LDL\Framework\Base\Collection\CallableCollection;

echo "Find compiler passes, no exception must be thrown\n\n";

$finder = new CompilerPassFileFinder(
    CompilerPassFileFinderOptions::fromArray([
        'directories' => [__DIR__.'/Build'],
    ]),
    new CallableCollection([
        static function ($c) {
            echo "Found compiler pass: $c\n";
        },
    ])
);

$files = $finder->find();

echo sprintf("\nFound %d files\n\n", count($files));
