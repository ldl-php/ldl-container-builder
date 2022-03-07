<?php

declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';

use LDL\DependencyInjection\CompilerPass\Finder\Options\CompilerPassFileFinderOptions;
use LDL\File\Helper\DirectoryHelper;

echo "This example finds compiler passes and compiles them\n\n";

$options = CompilerPassFileFinderOptions::fromArray([
    'directories' => [__DIR__.'/Build'],
]);

dump($options->toArray());

$dir = DirectoryHelper::getSysTempDir();
$file = $options->write($dir->mkpath('compiler_pass_options.json'));
$options = CompilerPassFileFinderOptions::fromJSONFile((string) $file);

dump($options->toArray());

$file->delete();
