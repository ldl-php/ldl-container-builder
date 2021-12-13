<?php

declare(strict_types=1);

namespace LDL\DependencyInjection\Service\Compiler\Directive;

use LDL\File\Contracts\FileInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

interface ServiceCompilerDirectiveInterface
{
    public function compile(
        ContainerBuilder $builder,
        FileInterface $file,
        array $definedServices
    ): void;
}
