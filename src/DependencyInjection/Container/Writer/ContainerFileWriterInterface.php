<?php

declare(strict_types=1);

namespace LDL\DependencyInjection\Container\Writer;

interface ContainerFileWriterInterface
{
    /**
     * @param string $content
     * @throws Exception\FileAlreadyExistsException
     */
    public function write(string $content): void;
}