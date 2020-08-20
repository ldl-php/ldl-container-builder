<?php

declare(strict_types=1);

namespace LDL\DependencyInjection\Service\Compiler;

use LDL\DependencyInjection\CompilerPass\Reader\CompilerPassReaderInterface;
use LDL\DependencyInjection\Service\Reader\ServiceFileReaderInterface;
use LDL\FS\Type\AbstractFileType;
use LDL\FS\Type\Types\Generic\Collection\GenericFileCollection;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ServiceCompiler implements ServiceCompilerInterface
{
    /**
     * @var Options\ServiceCompilerOptions
     */
    private $options;

    public function __construct(Options\ServiceCompilerOptions $options = null)
    {
        $this->options = $options ?? Options\ServiceCompilerOptions::fromArray([]);
    }

    public function compile(
        ContainerBuilder $container,
        GenericFileCollection $serviceFiles,
        ServiceFileReaderInterface $reader,
        GenericFileCollection $compilerPassFiles,
        CompilerPassReaderInterface $compilerPassReader
    ) : void
    {

        if($this->options->getOnBeforeCompile()){
            $this->options->getOnBeforeCompile()($container, $serviceFiles, $compilerPassFiles);
        }

        /**
         * @var AbstractFileType $file
         */
        foreach($compilerPassFiles as $file){
            $compilerPassReader->read($container, $file);
        }

        /**
         * @var AbstractFileType $file
         */
        foreach($serviceFiles as $file){
            $reader->read($container, $file);

            if($this->options->getOnCompile()){
                $this->options->getOnCompile()($container, $file);
            }
        }

        if($this->options->getOnAfterCompile()){
            $this->options->getOnAfterCompile()($container, $serviceFiles, $compilerPassFiles);
        }

        try{
            $container->compile();
        }catch(\Exception $e){
            throw new Exception\CompileErrorException($e->getMessage());
        }

    }

    /**
     * {@inheritdoc}
     */
    public function getOptions(): Options\ServiceCompilerOptions
    {
        return $this->options;
    }
}
