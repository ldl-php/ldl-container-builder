<?php declare(strict_types=1);

namespace LDL\DependencyInjection\Container\Builder;

use LDL\DependencyInjection\CompilerPass\Finder\CompilerPassFinderInterface;
use LDL\DependencyInjection\CompilerPass\Parser\CompilerPassParserInterface;
use LDL\DependencyInjection\Service\Compiler\Exception\CompileErrorException;
use LDL\DependencyInjection\Service\Compiler\ServiceCompilerInterface;
use LDL\DependencyInjection\Container\Writer\Exception\FileAlreadyExistsException;
use LDL\DependencyInjection\Service\File\Finder\ServiceFileFinderInterface;
use LDL\DependencyInjection\Service\File\Parser\ServiceFileParserInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

interface LDLContainerBuilderInterface
{
    /**
     * @throws CompileErrorException
     * @throws FileAlreadyExistsException
     */
    public function build(): ContainerBuilder;

    /**
     * @return ServiceFileFinderInterface
     */
    public function getServiceFinder() : ServiceFileFinderInterface;

    /**
     * @return ServiceFileParserInterface
     */
    public function getServiceReader(): ServiceFileParserInterface;

    /**
     * @return ServiceCompilerInterface
     */
    public function getServiceCompiler(): ServiceCompilerInterface;

    /**
     * @return CompilerPassFinderInterface
     */
    public function getCompilerPassFinder() : CompilerPassFinderInterface;

    /**
     * @return CompilerPassParserInterface
     */
    public function getCompilerPassReader() : CompilerPassParserInterface;
}