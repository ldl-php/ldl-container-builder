<?php

declare(strict_types=1);

namespace LDL\DependencyInjection\Service\Finder;

use LDL\FS\Type\Types\Generic\Collection\GenericFileCollection;

interface ServiceFileFinderInterface
{
    /**
     * @param bool $cache
     * @return GenericFileCollection
     * @throws Exception\NoFilesFoundException
     */
    public function find(bool $cache = false) : GenericFileCollection;

    /**
     * @return Options\ServiceFileFinderOptions
     */
    public function getOptions(): Options\ServiceFileFinderOptions;
}