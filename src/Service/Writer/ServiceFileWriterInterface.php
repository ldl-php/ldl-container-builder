<?php

namespace LDL\Service\Writer;

interface ServiceFileWriterInterface
{
    /**
     * @param string $content
     * @throws Exception\FileAlreadyExistsException
     */
    public function write(string $content): void;
}