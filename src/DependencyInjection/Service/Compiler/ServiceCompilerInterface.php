<?php declare(strict_types=1);

namespace LDL\DependencyInjection\Service\Compiler;

use LDL\DependencyInjection\CompilerPass\Parser\CompilerPassParserInterface;
use LDL\FS\Type\FileCollection;
use LDL\DependencyInjection\Service\File\Parser\ServiceFileParserInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

interface ServiceCompilerInterface
{
    /**
     * @param ContainerBuilder $container
     * @param FileCollection $files
     * @param ServiceFileParserInterface $reader
     * @param FileCollection $compilerPassFiles,
     * @param CompilerPassParserInterface $compilerPassReader
     * @return void
     * @throws Exception\CompileErrorException
     */
    public function compile(
        ContainerBuilder $container,
        FileCollection $files,
        ServiceFileParserInterface $reader,
        FileCollection $compilerPassFiles,
        CompilerPassParserInterface $compilerPassReader
    ) : void;

    /**
     * @return Options\ServiceCompilerOptions
     */
    public function getOptions(): Options\ServiceCompilerOptions;
}