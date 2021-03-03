<?php declare(strict_types=1);

namespace LDL\DependencyInjection\Service\Compiler;

use LDL\DependencyInjection\CompilerPass\Parser\CompilerPassParserInterface;
use LDL\DependencyInjection\Service\File\Parser\ServiceFileParserInterface;
use LDL\FS\Type\FileCollection;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Finder\SplFileInfo;

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
        FileCollection $serviceFiles,
        ServiceFileParserInterface $reader,
        FileCollection $compilerPassFiles,
        CompilerPassParserInterface $compilerPassReader
    ) : void
    {

        if($this->options->getOnBeforeCompile()){
            $this->options->getOnBeforeCompile()($container, $serviceFiles, $compilerPassFiles);
        }

        /**
         * @var SplFileInfo $file
         */
        foreach($compilerPassFiles as $file){
            $compilerPassReader->parse($container, $file);
        }

        /**
         * @var SplFileInfo $file
         */
        foreach($serviceFiles as $file){
            $reader->parse($container, $file);

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
