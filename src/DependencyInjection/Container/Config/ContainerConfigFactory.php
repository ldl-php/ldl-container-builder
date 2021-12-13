<?php

declare(strict_types=1);

namespace LDL\DependencyInjection\Container\Config;

use LDL\DependencyInjection\CompilerPass\Finder\CompilerPassFileFinderInterface;
use LDL\DependencyInjection\CompilerPass\Finder\Exception\NoFilesFoundException as NoCompilerPassFoundException;
use LDL\DependencyInjection\CompilerPass\Reader\CompilerPassReaderInterface;
use LDL\DependencyInjection\Container\Writer\ContainerFileWriterInterface;
use LDL\DependencyInjection\Service\Compiler\ServiceCompilerInterface;
use LDL\DependencyInjection\Service\File\Finder\Exception\NoFilesFoundException as NoServicesFoundException;
use LDL\DependencyInjection\Service\File\Finder\ServiceFileFinderInterface;
use LDL\DependencyInjection\Service\Reader\ServiceFileReaderInterface;
use LDL\FS\Type\Types\Generic\Collection\GenericFileCollection;

class ContainerConfigFactory
{
    public static function factory(
        ServiceFileFinderInterface $serviceFileFinder,
        ServiceCompilerInterface $serviceCompiler,
        ServiceFileReaderInterface $serviceFileReader,
        CompilerPassFileFinderInterface $compilerPassFinder,
        CompilerPassReaderInterface $compilerPassReader,
        ContainerFileWriterInterface $containerFileWriter,
        array $dumpOptions = null,
        string $generatedAs = null,
        \DateTime $date = null
    ) {
        $utcTZ = new \DateTimeZone("UTC");

        try {
            $servicesFiles = $serviceFileFinder->find(true);
        } catch (NoServicesFoundException $e) {
            $servicesFiles = new GenericFileCollection();
        }

        $services = [];

        foreach ($servicesFiles as $file) {
            $services[] = (string) $file;
        }

        try {
            $compilerPassFiles = $compilerPassFinder->find(true);
        } catch (NoCompilerPassFoundException $e) {
            $compilerPassFiles = new GenericFileCollection();
        }

        $compilerPasses = [];

        foreach ($compilerPassFiles as $file) {
            $compilerPasses[] = (string) $file;
        }

        return ContainerConfig::fromArray([
            'generation' => [
                'containerFilename' => $containerFileWriter->getOptions()->getFilename(),
                'generatedAs' => $generatedAs ?? ContainerConfig::DEFAULT_GENERATED_FILENAME,
                'date' => null !== $date ? $date->setTimezone($utcTZ) : new \DateTime("now", $utcTZ),
            ],
            'container' => [
                'dump' => $dumpOptions ?? ContainerConfig::DEFAULT_DUMP_OPTIONS,
                'writer' => [
                    'options' => $containerFileWriter->getOptions()->toArray(),
                ],
            ],
            'services' => [
                'finder' => [
                    'options' => $serviceFileFinder->getOptions()->toArray(),
                    'files' => $services,
                ],
                'compiler' => [
                    'options' => $serviceCompiler->getOptions()->toArray(),
                ],
                'reader' => [
                    'options' => $serviceFileReader->getOptions()->toArray(),
                ],
            ],
            'compilerPass' => [
                'finder' => [
                    'options' => $compilerPassFinder->getOptions()->toArray(),
                    'files' => $compilerPasses,
                ],
                'reader' => [
                    'options' => $compilerPassReader->getOptions()->toArray(),
                ],
            ],
        ]);
    }
}
