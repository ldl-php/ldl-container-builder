<?php

declare(strict_types=1);

namespace LDL\DependencyInjection\Container\Builder;

use LDL\DependencyInjection\CompilerPass\Compiler\CompilerPassCompilerInterface;
use LDL\DependencyInjection\CompilerPass\File\CompilerPassFileCollection;
use LDL\DependencyInjection\Service\Compiler\ServiceCompilerInterface;
use LDL\DependencyInjection\Service\File\ServiceFileCollection;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class LDLContainerBuilder implements LDLContainerBuilderInterface
{
    /**
     * @var ServiceCompilerInterface
     */
    private $serviceCompiler;

    /**
     * @var CompilerPassCompilerInterface
     */
    private $compilerPassCompiler;

    public function __construct(
        ServiceCompilerInterface $serviceCompiler,
        CompilerPassCompilerInterface $compilerPassCompiler
    ) {
        $this->serviceCompiler = $serviceCompiler;
        $this->compilerPassCompiler = $compilerPassCompiler;
    }

    /**
     * {@inheritdoc}
     */
    public function build(
        ServiceFileCollection $serviceFiles,
        CompilerPassFileCollection $compilerPassFiles = null,
        ContainerBuilder $builder = null
    ): ContainerBuilder {
        $builder = $builder ?? new ContainerBuilder();

        $this->serviceCompiler->compile(
            $builder,
            $serviceFiles,
            $compilerPassFiles
        );

        if (null !== $compilerPassFiles) {
            $this->compilerPassCompiler->compile(
                $builder,
                $compilerPassFiles
            );
        }

        return $builder;
    }
}
