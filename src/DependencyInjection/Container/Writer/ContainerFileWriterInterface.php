<?php declare(strict_types=1);

namespace LDL\DependencyInjection\Container\Writer;

use LDL\DependencyInjection\Container\Config\ContainerConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;

interface ContainerFileWriterInterface
{
    /**
     * @param ContainerConfig $config
     * @param ContainerBuilder $container
     * @throws Exception\FileAlreadyExistsException
     */
    public function write(ContainerConfig $config, ContainerBuilder $container): void;

    /**
     * @throws Exception\FileAlreadyExistsException
     * @throws Exception\FileIsNotWritableException
     * @throws Exception\DirectoryIsNotWritableException
     */
    public function test(): void;

    /**
     * @return Options\ContainerWriterOptions
     */
    public function getOptions(): Options\ContainerWriterOptions;
}