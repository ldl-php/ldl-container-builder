<?php

declare(strict_types=1);

namespace LDL\DependencyInjection\CompilerPass\Reader;

use LDL\FS\Type\AbstractFileType;
use Symfony\Component\DependencyInjection\ContainerBuilder;

interface CompilerPassReaderInterface
{
    /**
     * @param ContainerBuilder $container
     * @param AbstractFileType $file
     * @return mixed
     */
    public function read(ContainerBuilder $container, AbstractFileType $file) : void;

    /**
     * @return Options\CompilerPassReaderOptions
     */
    public function getOptions(): Options\CompilerPassReaderOptions;
}