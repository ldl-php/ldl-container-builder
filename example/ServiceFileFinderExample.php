<?php

declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';

use LDL\DependencyInjection\Service\File\Finder\Options\ServiceFileFinderOptions;
use LDL\DependencyInjection\Service\File\Finder\ServiceFileFinder;
use LDL\Framework\Base\Collection\CallableCollection;

echo "Find service files, no exception must be thrown\n\n";

$finder = new ServiceFileFinder(
    ServiceFileFinderOptions::fromArray([
        'directories' => [__DIR__.'/Build'],
    ]),
    new CallableCollection([
        static function ($file) {
            echo "Found $file\n";
        },
    ])
);

$files = $finder->find();

echo sprintf("\nFound %d files\n\n", count($files));
