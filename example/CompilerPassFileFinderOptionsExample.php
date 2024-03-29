<?php

declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';

use LDL\DependencyInjection\CompilerPass\Finder\Options\CompilerPassFileFinderOptions;
use LDL\File\Helper\DirectoryHelper;

echo "This example tests CompilerPassFileFinderOptions\n\n";

$options = CompilerPassFileFinderOptions::fromArray([
    'directories' => [__DIR__.'/Build'],
]);

dump($options->toArray());

$dir = DirectoryHelper::getSysTempDir();
$file = $options->write($dir->mkpath('compiler_pass_options.json'));

echo "\nRecreate options from JSON file:\n\n";

$options = CompilerPassFileFinderOptions::fromJsonFile((string) $file);

dump($options->toArray());

$file->delete();
