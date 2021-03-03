<?php declare(strict_types=1);

namespace LDL\DependencyInjection\Service\File\Finder;

use LDL\FS\Type\FileCollection;

interface ServiceFileFinderInterface
{
    /**
     * @param bool $cache
     * @return FileCollection
     */
    public function find(bool $cache = false) : FileCollection;

    /**
     * @return Options\ServiceFileFinderOptions
     */
    public function getOptions(): Options\ServiceFileFinderOptions;
}