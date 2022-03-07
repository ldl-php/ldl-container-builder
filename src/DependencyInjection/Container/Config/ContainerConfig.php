<?php

declare(strict_types=1);

namespace LDL\DependencyInjection\Container\Config;

use LDL\DependencyInjection\Interfaces\JSONOptionsInterface;
use LDL\DependencyInjection\Interfaces\OptionsInterface;
use LDL\DependencyInjection\Interfaces\WriteOptionsInterface;
use LDL\File\Contracts\FileInterface;
use LDL\File\File;

class ContainerConfig implements OptionsInterface, WriteOptionsInterface, JSONOptionsInterface
{
    public const DEFAULT_CONTAINER_FILENAME = 'container.php';

    public const DEFAULT_GENERATED_FILENAME = 'container-config.json';

    public const DEFAULT_DUMP_OPTIONS = [
        'namespace' => 'LDL\\DependencyInjection',
        'class' => 'LDLContainer',
        'format' => 'php',
    ];

    /**
     * @var string
     */
    private $containerFileName = self::DEFAULT_CONTAINER_FILENAME;

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

    public static function fromArray(array $options): self
    {
        $instance = new static();
        $defaults = get_object_vars($instance);
        $merge = array_replace_recursive($defaults, $options);

        return $instance->setContainerFileName($merge['generation']['containerFilename'])
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

    public function toArray(bool $useKeys = null): array
    {
        return [
            'generation' => [
                'containerFilename' => $this->getContainerFileName(),
                'generatedAs' => $this->getGeneratedAs(),
                'date' => $this->getDate()->format(\DateTimeInterface::W3C),
            ],
            'container' => [
                'dump' => $this->getDumpOptions(),
                'writer' => [
                    'options' => $this->getContainerWriter(),
                ],
            ],
            'services' => [
                'finder' => [
                    'options' => $this->getServiceFinder(),
                    'files' => $this->getServiceFiles(),
                ],
                'compiler' => [
                    'options' => $this->getServiceCompiler(),
                ],
                'reader' => [
                    'options' => $this->getServiceReader(),
                ],
            ],
            'compilerPass' => [
                'finder' => [
                    'options' => $this->getCompilerPassFinder(),
                    'files' => $this->getCompilerPassFiles(),
                ],
                'reader' => [
                    'options' => $this->getCompilerPassReader(),
                ],
            ],
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function getContainerFileName(): string
    {
        return $this->containerFileName;
    }

    private function setContainerFileName(string $containerFileName): ContainerConfig
    {
        $this->containerFileName = $containerFileName;

        return $this;
    }

    public function getGeneratedAs(): string
    {
        return $this->generatedAs;
    }

    private function setGeneratedAs(string $generatedAs): ContainerConfig
    {
        $this->generatedAs = $generatedAs;

        return $this;
    }

    public function getDumpOptions(): array
    {
        return $this->dumpOptions;
    }

    private function setDumpOptions(array $dumpOptions): ContainerConfig
    {
        $this->dumpOptions = $dumpOptions;

        return $this;
    }

    public function getServiceFiles(): array
    {
        return $this->serviceFiles;
    }

    private function setServiceFiles(array $serviceFiles): ContainerConfig
    {
        $this->serviceFiles = $serviceFiles;

        return $this;
    }

    public function getCompilerPassFiles(): array
    {
        return $this->compilerPassFiles;
    }

    private function setCompilerPassFiles(array $compilerPassFiles): ContainerConfig
    {
        $this->compilerPassFiles = $compilerPassFiles;

        return $this;
    }

    public function getServiceFinder(): array
    {
        return $this->serviceFinder;
    }

    private function setServiceFinder(array $serviceFinder): ContainerConfig
    {
        $this->serviceFinder = $serviceFinder;

        return $this;
    }

    public function getServiceCompiler(): array
    {
        return $this->serviceCompiler;
    }

    private function setServiceCompiler(array $serviceCompiler): ContainerConfig
    {
        $this->serviceCompiler = $serviceCompiler;

        return $this;
    }

    public function getServiceReader(): array
    {
        return $this->serviceReader;
    }

    private function setServiceReader(array $serviceReader): ContainerConfig
    {
        $this->serviceReader = $serviceReader;

        return $this;
    }

    public function getCompilerPassFinder(): array
    {
        return $this->compilerPassFinder;
    }

    private function setCompilerPassFinder(array $compilerPassFinder): ContainerConfig
    {
        $this->compilerPassFinder = $compilerPassFinder;

        return $this;
    }

    public function getCompilerPassReader(): array
    {
        return $this->compilerPassReader;
    }

    private function setCompilerPassReader(array $compilerPassReader): ContainerConfig
    {
        $this->compilerPassReader = $compilerPassReader;

        return $this;
    }

    public function getContainerWriter(): array
    {
        return $this->containerWriter;
    }

    private function setContainerWriter(array $containerWriter): ContainerConfig
    {
        $this->containerWriter = $containerWriter;

        return $this;
    }

    public function getDate(): \DateTime
    {
        return $this->date;
    }

    private function setDate(\DateTime $date): ContainerConfig
    {
        $this->date = $date;

        return $this;
    }

    public function write(string $path, bool $overwrite = true): FileInterface
    {
        return File::create(
            $path,
            json_encode($this, \JSON_THROW_ON_ERROR | \JSON_PRETTY_PRINT),
            0644,
            $overwrite
        );
    }

    public static function fromJSONFile(string $file): ContainerConfig
    {
        return self::fromJSON((new File($file))->getLinesAsString());
    }

    public static function fromJSON(string $json): ContainerConfig
    {
        return self::fromArray(json_decode($json, true, 2048, \JSON_THROW_ON_ERROR));
    }
}
