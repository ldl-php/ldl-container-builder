<?php declare(strict_types=1);

namespace LDL\DependencyInjection\CompilerPass\Compiler;

use LDL\DependencyInjection\CompilerPass\File\CompilerPassFileCollection;
use Symfony\Component\DependencyInjection\ContainerBuilder;

interface CompilerPassCompilerInterface
{
    /**
     * @param ContainerBuilder $container
     * @param CompilerPassFileCollection $compilerPassFiles
     * @return void
     * @throws Exception\CompileErrorException
     */
    public function compile(
        ContainerBuilder $container,
        CompilerPassFileCollection $compilerPassFiles
    ) : void;

    /**
     * @return Options\CompilerPassCompilerOptions
     */
    public function getOptions(): Options\CompilerPassCompilerOptions;
}