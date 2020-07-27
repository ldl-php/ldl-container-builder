<?php

declare(strict_types=1);

namespace LDL\DependencyInjection\Container\Builder;

use LDL\DependencyInjection\Container\Compiler\Exception\CompileErrorException;
use LDL\DependencyInjection\Service\Finder\Exception\NoFilesFoundException as NoServicesFoundException;
use LDL\DependencyInjection\Container\Writer\Exception\FileAlreadyExistsException;
use Symfony\Component\DependencyInjection\ContainerBuilder;

interface BuilderInterface
{
    /**
     * @throws CompileErrorException
     * @throws NoServicesFoundException
     * @throws FileAlreadyExistsException
     */
    public function build(): ContainerBuilder;
}