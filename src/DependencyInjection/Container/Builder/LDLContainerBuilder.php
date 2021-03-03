<?php declare(strict_types=1);

namespace LDL\DependencyInjection\Container\Builder;

use LDL\DependencyInjection\Service\Compiler\ServiceCompiler;
use LDL\DependencyInjection\Service\Compiler\ServiceCompilerInterface;
use LDL\DependencyInjection\CompilerPass\Finder\CompilerPassFinder;
use LDL\DependencyInjection\CompilerPass\Finder\CompilerPassFinderInterface;
use LDL\DependencyInjection\CompilerPass\Parser\CompilerPassParser;
use LDL\DependencyInjection\CompilerPass\Parser\CompilerPassParserInterface;
use LDL\DependencyInjection\Service\File\Finder\ServiceFileFinder;
use LDL\DependencyInjection\Service\File\Finder\ServiceFileFinderInterface;
use LDL\DependencyInjection\Service\File\Parser\ServiceFileParser;
use LDL\DependencyInjection\Service\File\Parser\ServiceFileParserInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class LDLContainerBuilder implements LDLContainerBuilderInterface
{
    /**
     * @var ServiceFileFinderInterface
     */
    private $serviceFileFinder;

    /**
     * @var ServiceFileParserInterface
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
     * @var CompilerPassParserInterface
     */
    private $compilerPassFileReader;

    public function __construct(
        ServiceFileFinderInterface $finder = null,
        ServiceCompilerInterface $compiler = null,
        ServiceFileParserInterface $reader = null,
        CompilerPassFinderInterface $compilerPassFinder = null,
        CompilerPassParserInterface $compilerPassReader = null
    )
    {
        $this->serviceFileFinder      = $finder   ?? new ServiceFileFinder();
        $this->serviceCompiler        = $compiler ?? new ServiceCompiler();
        $this->serviceFileReader      = $reader   ?? new ServiceFileParser();

        $this->compilerPassFileFinder = $compilerPassFinder ?? new CompilerPassFinder();
        $this->compilerPassFileReader = $compilerPassReader ?? new CompilerPassParser();
    }

    /**
     * {@inheritdoc}
     */
    public function build(): ContainerBuilder
    {
        $builder = new ContainerBuilder();

        $this->serviceCompiler->compile(
            $builder,
            $this->serviceFileFinder->find(),
            $this->serviceFileReader,
            $this->compilerPassFileFinder->find(),
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
    public function getServiceReader(): ServiceFileParserInterface
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
    public function getCompilerPassReader() : CompilerPassParserInterface
    {
        return $this->compilerPassFileReader;
    }
}