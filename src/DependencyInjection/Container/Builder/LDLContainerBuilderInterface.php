<?php

declare(strict_types=1);

namespace LDL\DependencyInjection\Container\Builder;

use LDL\DependencyInjection\CompilerPass\File\CompilerPassFileCollection;
use LDL\DependencyInjection\Service\File\ServiceFileCollection;
use Symfony\Component\DependencyInjection\ContainerBuilder;

interface LDLContainerBuilderInterface
{
    public function build(
        ServiceFileCollection $serviceFiles,
        CompilerPassFileCollection $compilerPassFiles = null,
        ContainerBuilder $builder = null
    ): ContainerBuilder;
}
