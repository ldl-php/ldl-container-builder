<?php

declare(strict_types=1);

namespace LDL\DependencyInjection\Container\Builder;

use LDL\DependencyInjection\CompilerPass\Finder\Exception\NoFilesFoundException as NoCompilerPassFound;
use LDL\DependencyInjection\Service\Compiler\ServiceCompiler;
use LDL\DependencyInjection\Service\Compiler\ServiceCompilerInterface;
use LDL\DependencyInjection\CompilerPass\Finder\CompilerPassFinder;
use LDL\DependencyInjection\CompilerPass\Finder\CompilerPassFinderInterface;
use LDL\DependencyInjection\CompilerPass\Reader\CompilerPassReader;
use LDL\DependencyInjection\CompilerPass\Reader\CompilerPassReaderInterface;
use LDL\DependencyInjection\Service\Finder\ServiceFileFinder;
use LDL\DependencyInjection\Service\Finder\ServiceFileFinderInterface;
use LDL\DependencyInjection\Service\Reader\ServiceFileReader;
use LDL\DependencyInjection\Service\Reader\ServiceFileReaderInterface;
use LDL\DependencyInjection\Container\Writer\ContainerFileWriter;
use LDL\DependencyInjection\Container\Writer\ContainerFileWriterInterface;
use LDL\FS\Type\Types\Generic\Collection\GenericFileCollection;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class LDLContainerBuilder implements LDLContainerBuilderInterface
{
    /**
     * @var ServiceFileFinderInterface
     */
    private $serviceFileFinder;

    /**
     * @var ServiceFileReaderInterface
     */
    private $serviceFileReader;

    /**
     * @var ServiceCompilerInterface
     */
    private $serviceCompiler;

    /**
     * @var ContainerFileWriterInterface
     */
    private $containerFileWriter;

    /**
     * @var CompilerPassFinderInterface
     */
    private $compilerPassFileFinder;

    /**
     * @var CompilerPassReaderInterface
     */
    private $compilerPassFileReader;

    public function __construct(
        ServiceFileFinderInterface $finder = null,
        ServiceCompilerInterface $compiler = null,
        ContainerFileWriterInterface $writer = null,
        ServiceFileReaderInterface $reader = null,
        CompilerPassFinderInterface $compilerPassFinder = null,
        CompilerPassReaderInterface $compilerPassReader = null
    )
    {
        $this->serviceFileFinder      = $finder   ?? new ServiceFileFinder();
        $this->serviceCompiler        = $compiler ?? new ServiceCompiler();
        $this->serviceFileReader      = $reader   ?? new ServiceFileReader();
        $this->containerFileWriter      = $writer   ?? new ContainerFileWriter();

        $this->compilerPassFileFinder = $compilerPassFinder ?? new CompilerPassFinder();
        $this->compilerPassFileReader = $compilerPassReader ?? new CompilerPassReader();
    }

    /**
     * {@inheritdoc}
     */
    public function build(): ContainerBuilder
    {
        $this->containerFileWriter->test();

        $serviceFiles = $this->serviceFileFinder->find();

        try{
            $compilerPassFiles = $this->compilerPassFileFinder->find();
        }catch(NoCompilerPassFound $e){
            $compilerPassFiles = new GenericFileCollection();
        }

        $builder = new ContainerBuilder();

        $compiled = $this->serviceCompiler->compile(
            $builder,
            $serviceFiles,
            $this->serviceFileReader,
            $compilerPassFiles,
            $this->compilerPassFileReader
        );

        $this->containerFileWriter->write($compiled);

        return $builder;
    }

    /**
     * @return ServiceFileFinderInterface
     */
    public function getServiceFinder() : ServiceFileFinderInterface
    {
        return $this->serviceFileFinder;
    }

    /**
     * @return ServiceFileReaderInterface
     */
    public function getServiceReader(): ServiceFileReaderInterface
    {
        return $this->serviceFileReader;
    }

    /**
     * @return ServiceCompilerInterface
     */
    public function getServiceCompiler(): ServiceCompilerInterface
    {
        return $this->serviceCompiler;
    }

    /**
     * @return CompilerPassFinderInterface
     */
    public function getCompilerPassFinder() : CompilerPassFinderInterface
    {
        return $this->compilerPassFileFinder;
    }

    /**
     * @return CompilerPassReaderInterface
     */
    public function getCompilerPassReader() : CompilerPassReaderInterface
    {
        return $this->compilerPassFileReader;
    }

    /**
     * @return ContainerFileWriterInterface
     */
    public function getContainerWriter(): ContainerFileWriterInterface
    {
        return $this->containerFileWriter;
    }
}