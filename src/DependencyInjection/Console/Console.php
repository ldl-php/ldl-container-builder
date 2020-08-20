<?php declare(strict_types=1);

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
        parent::__construct('<info>[ LDL Container builder ]</info>', $version);

        $commands = LocalFileFinder::findRegex(
            '^.*Command\.php$',
            [
                Path::make(__DIR__, 'Command')
            ]
        );

        $commands = array_map(function($item) {
            return $item->getRealPath();
        },\iterator_to_array($commands));

        usort($commands, function($a, $b){
            return strcmp($a, $b);
        });

        /**
         * @var \SplFileInfo $commandFile
         */
        foreach($commands as $key => $commandFile){
            /**
             * Skip abstract class, there is no need to require it due to autoloader kicking in
             */
            if(0 === $key){
                continue;
            }

            require_once $commandFile;

            $class = get_declared_classes();
            $class = $class[count($class) - 1];

            $this->add(new $class());
        }
    }
}
