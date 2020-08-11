<?php

declare(strict_types=1);

namespace LDL\DependencyInjection\CompilerPass\Finder;

use LDL\FS\Type\Types\Generic\Collection\GenericFileCollection;

interface CompilerPassFinderInterface
{
    /**
     * @param bool $cache
     * @return GenericFileCollection
     * @throws Exception\NoFilesFoundException
     */
    public function find(bool $cache = false): GenericFileCollection;

    /**
     * @return Options\CompilerPassFinderOptions
     */
    public function getOptions() : Options\CompilerPassFinderOptions;
}