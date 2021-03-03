<?php declare(strict_types=1);

namespace LDL\DependencyInjection\CompilerPass\Finder;

use LDL\DependencyInjection\CompilerPass\Collection\CompilerPassCollectionInterface;

interface CompilerPassFinderInterface
{
    /**
     * @param bool $cache
     * @return CompilerPassCollectionInterface
     */
    public function find(bool $cache = false): CompilerPassCollectionInterface;

    /**
     * @return Options\CompilerPassFinderOptions
     */
    public function getOptions() : Options\CompilerPassFinderOptions;
}