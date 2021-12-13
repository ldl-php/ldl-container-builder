<?php

declare(strict_types=1);

namespace LDL\DependencyInjection\Service\Compiler;

use LDL\DependencyInjection\Service\File\ServiceFileCollection;
use Symfony\Component\DependencyInjection\ContainerBuilder;

interface ServiceCompilerInterface
{
    /**
     * @throws Exception\CompileErrorException
     */
    public function compile(
        ContainerBuilder $container,
        ServiceFileCollection $serviceFiles
    ): void;

    public function getOptions(): Options\ServiceCompilerOptions;
}
