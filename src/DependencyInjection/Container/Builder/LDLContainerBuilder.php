<?php declare(strict_types=1);

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
        ServiceFileReaderInterface $reader = null,
        CompilerPassFinderInterface $compilerPassFinder = null,
        CompilerPassReaderInterface $compilerPassReader = null
    )
    {
        $this->serviceFileFinder      = $finder   ?? new ServiceFileFinder();
        $this->serviceCompiler        = $compiler ?? new ServiceCompiler();
        $this->serviceFileReader      = $reader   ?? new ServiceFileReader();

        $this->compilerPassFileFinder = $compilerPassFinder ?? new CompilerPassFinder();
        $this->compilerPassFileReader = $compilerPassReader ?? new CompilerPassReader();
    }

    /**
     * {@inheritdoc}
     */
    public function build(): ContainerBuilder
    {
        $serviceFiles = $this->serviceFileFinder->find();

        try{
            $compilerPassFiles = $this->compilerPassFileFinder->find();
        }catch(NoCompilerPassFound $e){
            $compilerPassFiles = new GenericFileCollection();
        }

        $builder = new ContainerBuilder();

        $this->serviceCompiler->compile(
            $builder,
            $serviceFiles,
            $this->serviceFileReader,
            $compilerPassFiles,
            $this->compilerPassFileReader
        );

        return $builder;
    }

    /**
     * {@inheritdoc}
     */
    public function getServiceFinder() : ServiceFileFinderInterface
    {
        return $this->serviceFileFinder;
    }

    /**
     * {@inheritdoc}
     */
    public function getServiceReader(): ServiceFileReaderInterface
    {
        return $this->serviceFileReader;
    }

    /**
     * {@inheritdoc}
     */
    public function getServiceCompiler(): ServiceCompilerInterface
    {
        return $this->serviceCompiler;
    }

    /**
     * {@inheritdoc}
     */
    public function getCompilerPassFinder() : CompilerPassFinderInterface
    {
        return $this->compilerPassFileFinder;
    }

    /**
     * {@inheritdoc}
     */
    public function getCompilerPassReader() : CompilerPassReaderInterface
    {
        return $this->compilerPassFileReader;
    }
}