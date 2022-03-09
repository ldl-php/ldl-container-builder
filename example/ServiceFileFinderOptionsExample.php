<?php

declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';

use LDL\DependencyInjection\Service\File\Finder\Options\ServiceFileFinderOptions;
use LDL\File\Helper\DirectoryHelper;

echo "Test ServiceFileFinderOptions\n\n";

$options = ServiceFileFinderOptions::fromArray([
    'directories' => [__DIR__.'/Build'],
]);

dump($options->toArray());

$dir = DirectoryHelper::getSysTempDir();
$file = $options->write($dir->mkpath('service_file_finder_options.json'));
$options = ServiceFileFinderOptions::fromJsonFile((string) $file);

dump($options->toArray());

$file->delete();
