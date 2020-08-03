<?php

declare(strict_types=1);

namespace LDL\DependencyInjection\Service\Compiler;

use LDL\DependencyInjection\CompilerPass\Reader\CompilerPassReaderInterface;
use LDL\FS\Type\Types\Generic\Collection\GenericFileCollection;
use LDL\DependencyInjection\Service\Reader\ServiceFileReaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

interface ServiceCompilerInterface
{
    /**
     * @param ContainerBuilder $container
     * @param GenericFileCollection $files
     * @param GenericFileCollection $compilerPassFiles,
     * @param ServiceFileReaderInterface $reader
     * @param CompilerPassReaderInterface $compilerPassReader
     * @return string
     * @throws Exception\CompileErrorException
     */
    public function compile(
        ContainerBuilder $container,
        GenericFileCollection $files,
        ServiceFileReaderInterface $reader,
        GenericFileCollection $compilerPassFiles,
        CompilerPassReaderInterface $compilerPassReader
    ) : string;

    /**
     * @return Options\ServiceCompilerOptions
     */
    public function getOptions(): Options\ServiceCompilerOptions;
}