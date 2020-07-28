<?php

declare(strict_types=1);

namespace LDL\DependencyInjection\Service\Finder;

use LDL\FS\Type\Types\Generic\Collection\GenericFileCollection;

interface ServiceFileFinderInterface
{
    /**
     * @param Options\ServiceFileFinderOptions $options
     * @return GenericFileCollection
     * @throws Exception\NoFilesFoundException
     */
    public function find() : GenericFileCollection;

    /**
     * @return Options\ServiceFileFinderOptions
     */
    public function getOptions(): Options\ServiceFileFinderOptions;
}