<?php

declare(strict_types=1);

namespace LDL\DependencyInjection\Console;

use LDL\FS\Finder\Adapter\LocalFileFinder;
use LDL\FS\Util\Path;
use Symfony\Component\Console\Application as SymfonyApplication;

class Console extends SymfonyApplication
{
    /**
     * Console constructor.
     * @param string $name
     * @param string $version
     */
    public function __construct(string $name = 'UNKNOWN', string $version = 'UNKNOWN')
    {
        parent::__construct('<info>[ Services file builder ]</info>', $version);

        $commands = LocalFileFinder::findRegex(
            '^.*Command\.php$',
            [
                Path::make(__DIR__, '..')
            ]
        );

        $commands = array_map(function($item) {
            return $item->getRealPath();
        },\iterator_to_array($commands));

        /**
         * @var \SplFileInfo $commandFile
         */
        foreach($commands as $key => $commandFile){
            require_once $commandFile;

            $class = get_declared_classes();
            $class = $class[count($class) - 1];

            $this->add(new $class());
        }
    }
}
