<?php

namespace LDL\Service\Builder;

use LDL\Service\Compiler\ServiceCompiler;
use LDL\Service\Compiler\ServiceCompilerInterface;
use LDL\Service\Compiler\Options\ServiceCompilerOptions;
use LDL\Service\Finder\ServiceFileFinder;
use LDL\Service\Finder\ServiceFileFinderInterface;
use LDL\Service\Finder\Options\ServiceFileFinderOptions;
use LDL\Service\Reader\ServiceReader;
use LDL\Service\Writer\ServiceFileWriter;
use LDL\Service\Writer\ServiceFileWriterInterface;
use LDL\Service\Writer\Options\ServiceWriterOptions;

class ServiceBuilder implements ServiceBuilderInterface
{
    /**
     * @var ServiceFileFinderInterface
     */
    private $serviceFileFinder;

    /**
     * @var ServiceCompilerInterface
     */
    private $serviceCompiler;

    /**
     * @var ServiceFileWriterInterface
     */
    private $serviceFileWriter;

    public function __construct(
        ServiceFileFinderInterface $serviceFileFinder = null,
        ServiceCompilerInterface $serviceCompiler = null,
        ServiceFileWriterInterface $serviceFileWriter = null
    )
    {
        $this->serviceFileFinder = $serviceFileFinder ?? new ServiceFileFinder();
        $this->serviceCompiler = $serviceCompiler ?? new ServiceCompiler();
        $this->serviceFileWriter = $serviceFileWriter ?? new ServiceFileWriter();
    }

    /**
     * {@inheritdoc}
     */
    public function build(
        ServiceFileFinderOptions $finderOptions = null,
        ServiceCompilerOptions $compilerOptions = null,
        ServiceWriterOptions $writerOptions = null
    ): void
    {
        $files = $this->serviceFileFinder->find($finderOptions);

        $reader = new ServiceReader();

        $compiled = $this->serviceCompiler->compile(
            $files,
            $reader,
            $compilerOptions
        );

        $this->serviceFileWriter->write($compiled, $writerOptions);
    }
}