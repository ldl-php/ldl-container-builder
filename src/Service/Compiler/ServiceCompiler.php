<?php

namespace LDL\Service\Compiler;

use LDL\Service\Compiler\Options\ServiceCompilerOptions;
use LDL\Service\Reader\ServiceReaderInterface;
use LDL\Service\Reader\Options\ServiceReaderOptions;
use LDL\FS\Type\AbstractFileType;
use LDL\FS\Type\Types\Generic\Collection\GenericFileCollection;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ServiceCompiler implements ServiceCompilerInterface
{
    /**
     * @var ServiceCompilerOptions
     */
    private $options;

    private $contents = [];

    public function compile(
        GenericFileCollection $files,
        ServiceReaderInterface $reader,
        ServiceCompilerOptions $options = null
    ) : string
    {
        $this->options = $options ?? Options\ServiceCompilerOptions::fromArray([]);

        $container = new ContainerBuilder();

        /**
         * @var AbstractFileType $file
         */
        foreach($files as $file){
            $options  = ServiceReaderOptions::fromArray([
               'file' => $file
            ]);

            $reader->read($container, $options);

            $return = [];

            foreach($this->contents as $filePath => $vars){
                if($this->options->commentsEnabled()){
                    $return[] = "#Taken from $filePath";
                }

                $return[] = implode("\n",$vars);
            }
        }

        return implode("\n",$return);

    }
}
