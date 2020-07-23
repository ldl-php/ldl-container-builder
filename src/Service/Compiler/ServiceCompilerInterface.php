<?php

namespace LDL\Service\Compiler;

use LDL\Service\Compiler\Options\ServiceCompilerOptions;
use LDL\FS\Type\Types\Generic\Collection\GenericFileCollection;
use LDL\Service\Reader\ServiceReaderInterface;

interface ServiceCompilerInterface
{
    /**
     * @param GenericFileCollection $files
     * @param ServiceReaderInterface $reader
     * @param ServiceCompilerOptions|null $options
     * @return string
     */
    public function compile(
        GenericFileCollection $files,
        ServiceReaderInterface $reader,
        ServiceCompilerOptions $options = null
    ) : string;
}