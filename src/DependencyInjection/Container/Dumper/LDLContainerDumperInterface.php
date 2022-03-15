<?php

declare(strict_types=1);

namespace LDL\DependencyInjection\Container\Dumper;

use LDL\DependencyInjection\Container\Options\ContainerDumpOptionsInterface;
use LDL\File\Contracts\FileInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

interface LDLContainerDumperInterface
{
    public static function dump(
        string $format,
        ContainerBuilder $container,
        ContainerDumpOptionsInterface $options,
        FileInterface $file
    ): string;
}
