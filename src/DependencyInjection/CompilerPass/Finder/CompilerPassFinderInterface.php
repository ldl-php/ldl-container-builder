<?php

declare(strict_types=1);

namespace LDL\DependencyInjection\CompilerPass\Finder;

use LDL\FS\Type\Types\Generic\Collection\GenericFileCollection;

interface CompilerPassFinderInterface
{
    /**
     * @return GenericFileCollection
     * @throws Exception\NoFilesFoundException
     */
    public function find(): GenericFileCollection;

    /**
     * @return Options\CompilerPassFinderOptions
     */
    public function getOptions() : Options\CompilerPassFinderOptions;
}