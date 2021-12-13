<?php declare(strict_types=1);

namespace LDL\DependencyInjection\CompilerPass\Compiler;

use LDL\DependencyInjection\CompilerPass\File\CompilerPassFileCollection;
use LDL\DependencyInjection\CompilerPass\LDLCompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class CompilerPassCompiler implements CompilerPassCompilerInterface
{
    /**
     * @var Options\CompilerPassCompilerOptions
     */
    private $options;

    public function __construct(Options\CompilerPassCompilerOptions $options=null)
    {
        $this->options = $options ?? Options\CompilerPassCompilerOptions::fromArray([]);
    }

    public function compile(
        ContainerBuilder $container,
        CompilerPassFileCollection $compilerPasses
    ) : void
    {
        if($this->options->getOnBeforeCompile()){
            $this->options->getOnBeforeCompile()($container, $compilerPasses);
        }

        /**
         * @var LDLCompilerPassInterface $compilerPass
         */
        foreach($compilerPasses->getCompilerPassInstances() as $compilerPass){
            $container->addCompilerPass($compilerPass, $compilerPass->getType(), $compilerPass->getPriority());
        }

        if($this->options->getOnAfterCompile()){
            $this->options->getOnAfterCompile()($container, $compilerPasses);
        }

        try{
            $container->compile();
        }catch(\Exception $e){
            throw new Exception\CompilerPassCompilerException($e->getMessage());
        }

    }

    /**
     * {@inheritdoc}
     */
    public function getOptions(): Options\CompilerPassCompilerOptions
    {
        return $this->options;
    }
}
