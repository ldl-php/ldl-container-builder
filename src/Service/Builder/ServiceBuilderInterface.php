<?php

namespace LDL\Service\Builder;

use LDL\Service\Finder\Exception\NoFilesFoundException;
use LDL\Service\Writer\Exception\FileAlreadyExistsException;

interface ServiceBuilderInterface
{
    /**
     * @throws NoFilesFoundException
     * @throws FileAlreadyExistsException
     */
    public function build(): void;
}