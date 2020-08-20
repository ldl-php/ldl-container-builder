<?php declare(strict_types=1);

namespace LDL\DependencyInjection\Container\Config;

use LDL\DependencyInjection\Interfaces\OptionsInterface;

class ContainerConfig implements OptionsInterface
{
    public const DEFAULT_OUTPUT_FILENAME = 'container';

    public const DEFAULT_GENERATED_FILENAME = 'container-config.json';

    public const DEFAULT_DUMP_OPTIONS = [
        'namespace' => 'LDL\\DependencyInjection',
        'class' => 'LDLContainer',
        'format' => 'php'
    ];

    /**
     * @var string
     */
    private $outputFilename = self::DEFAULT_OUTPUT_FILENAME;

    /**
     * @var string
     */
    private $generatedAs = self::DEFAULT_GENERATED_FILENAME;

    /**
     * @var array
     */
    private $dumpOptions = self::DEFAULT_DUMP_OPTIONS;

    /**
     * @var array
     */
    private $serviceFiles = [];

    /**
     * @var array
     */
    private $compilerPassFiles = [];

    /**
     * @var array
     */
    private $serviceFinder = [];

    /**
     * @var array
     */
    private $serviceCompiler = [];

    /**
     * @var array
     */
    private $serviceReader = [];

    /**
     * @var array
     */
    private $compilerPassFinder = [];

    /**
     * @var array
     */
    private $compilerPassReader = [];

    /**
     * @var array
     */
    private $containerWriter = [];

    /**
     * @var \DateTime
     */
    private $date;

    public static function fromArray(array $options) : self
    {
        $instance = new static();
        $defaults = get_object_vars($instance);
        $merge = array_replace_recursive($defaults, $options);

        return $instance->setOutputFilename($merge['generation']['outputFilename'])
            ->setGeneratedAs($merge['generation']['generatedAs'])
            ->setDate($merge['generation']['date'])
            ->setDumpOptions($merge['container']['dump'])
            ->setContainerWriter($merge['container']['writer']['options'])
            ->setServiceFinder($merge['services']['finder']['options'])
            ->setServiceCompiler($merge['services']['compiler']['options'])
            ->setServiceReader($merge['services']['reader']['options'])
            ->setCompilerPassFinder($merge['compilerPass']['finder']['options'])
            ->setCompilerPassReader($merge['compilerPass']['reader']['options'])
            ->setServiceFiles($merge['services']['finder']['files'])
            ->setCompilerPassFiles($merge['compilerPass']['finder']['files']);
    }

    /**
     * @return array
     */
    public function toArray() : array
    {
        return [
            'generation' => [
                'outputFilename' => $this->getOutputFilename(),
                'generatedAs' => $this->getGeneratedAs(),
                'date' => $this->getDate()->format(\DateTimeInterface::W3C)
            ],
            'container' => [
                'dump' => $this->getDumpOptions(),
                'writer' => [
                    'options' => $this->getContainerWriter()
                ]
            ],
            'services' => [
                'finder' => [
                    'options' => $this->getServiceFinder(),
                    'files' => $this->getServiceFiles()
                ],
                'compiler' => [
                    'options' => $this->getServiceCompiler()
                ],
                'reader' => [
                    'options' => $this->getServiceReader()
                ]
            ],
            'compilerPass' => [
                'finder' => [
                    'options' => $this->getCompilerPassFinder(),
                    'files' => $this->getCompilerPassFiles()
                ],
                'reader' => [
                    'options' => $this->getCompilerPassReader()
                ]
            ]
        ];
    }

    /**
     * @return array
     */
    public function jsonSerialize() : array
    {
        return $this->toArray();
    }

    /**
     * @return string
     */
    public function getOutputFilename(): string
    {
        return $this->outputFilename;
    }

    /**
     * @param string $outputFilename
     * @return ContainerConfig
     */
    private function setOutputFilename(string $outputFilename): ContainerConfig
    {
        $this->outputFilename = $outputFilename;
        return $this;
    }

    /**
     * @return string
     */
    public function getGeneratedAs(): string
    {
        return $this->generatedAs;
    }

    /**
     * @param string $generatedAs
     * @return ContainerConfig
     */
    private function setGeneratedAs(string $generatedAs): ContainerConfig
    {
        $this->generatedAs = $generatedAs;
        return $this;
    }

    /**
     * @return array
     */
    public function getDumpOptions(): array
    {
        return $this->dumpOptions;
    }

    /**
     * @param array $dumpOptions
     * @return ContainerConfig
     */
    private function setDumpOptions(array $dumpOptions): ContainerConfig
    {
        $this->dumpOptions = $dumpOptions;
        return $this;
    }

    /**
     * @return array
     */
    public function getServiceFiles(): array
    {
        return $this->serviceFiles;
    }

    /**
     * @param array $serviceFiles
     * @return ContainerConfig
     */
    private function setServiceFiles(array $serviceFiles): ContainerConfig
    {
        $this->serviceFiles = $serviceFiles;
        return $this;
    }

    /**
     * @return array
     */
    public function getCompilerPassFiles(): array
    {
        return $this->compilerPassFiles;
    }

    /**
     * @param array $compilerPassFiles
     * @return ContainerConfig
     */
    private function setCompilerPassFiles(array $compilerPassFiles): ContainerConfig
    {
        $this->compilerPassFiles = $compilerPassFiles;
        return $this;
    }

    /**
     * @return array
     */
    public function getServiceFinder(): array
    {
        return $this->serviceFinder;
    }

    /**
     * @param array $serviceFinder
     * @return ContainerConfig
     */
    private function setServiceFinder(array $serviceFinder): ContainerConfig
    {
        $this->serviceFinder = $serviceFinder;
        return $this;
    }

    /**
     * @return array
     */
    public function getServiceCompiler(): array
    {
        return $this->serviceCompiler;
    }

    /**
     * @param array $serviceCompiler
     * @return ContainerConfig
     */
    private function setServiceCompiler(array $serviceCompiler): ContainerConfig
    {
        $this->serviceCompiler = $serviceCompiler;
        return $this;
    }

    /**
     * @return array
     */
    public function getServiceReader(): array
    {
        return $this->serviceReader;
    }

    /**
     * @param array $serviceReader
     * @return ContainerConfig
     */
    private function setServiceReader(array $serviceReader): ContainerConfig
    {
        $this->serviceReader = $serviceReader;
        return $this;
    }

    /**
     * @return array
     */
    public function getCompilerPassFinder(): array
    {
        return $this->compilerPassFinder;
    }

    /**
     * @param array $compilerPassFinder
     * @return ContainerConfig
     */
    private function setCompilerPassFinder(array $compilerPassFinder): ContainerConfig
    {
        $this->compilerPassFinder = $compilerPassFinder;
        return $this;
    }

    /**
     * @return array
     */
    public function getCompilerPassReader(): array
    {
        return $this->compilerPassReader;
    }

    /**
     * @param array $compilerPassReader
     * @return ContainerConfig
     */
    private function setCompilerPassReader(array $compilerPassReader): ContainerConfig
    {
        $this->compilerPassReader = $compilerPassReader;
        return $this;
    }

    /**
     * @return array
     */
    public function getContainerWriter(): array
    {
        return $this->containerWriter;
    }

    /**
     * @param array $containerWriter
     * @return ContainerConfig
     */
    private function setContainerWriter(array $containerWriter): ContainerConfig
    {
        $this->containerWriter = $containerWriter;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     * @return ContainerConfig
     */
    private function setDate(\DateTime $date): ContainerConfig
    {
        $this->date = $date;
        return $this;
    }
}