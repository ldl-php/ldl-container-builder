<?php

namespace LDL\DependencyInjection\Service\Reader;

use LDL\FS\Type\AbstractFileType;
use Symfony\Component\DependencyInjection\ContainerBuilder;

interface ServiceFileReaderInterface
{
    /**
     * @param ContainerBuilder $container
     * @param AbstractFileType $file
     * @return mixed
     */
    public function read(ContainerBuilder $container, AbstractFileType $file) : void;
}