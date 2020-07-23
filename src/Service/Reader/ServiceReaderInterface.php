<?php

namespace LDL\Service\Reader;

use Symfony\Component\DependencyInjection\ContainerBuilder;

interface ServiceReaderInterface
{
    /**
     * @param ContainerBuilder $container
     * @param Options\ServiceReaderOptions $options
     * @return mixed
     */
    public function read(ContainerBuilder $container, Options\ServiceReaderOptions $options);
}