<?php

declare(strict_types=1);

namespace LDL\DependencyInjection\CompilerPass\Finder;

use LDL\DependencyInjection\CompilerPass\File\CompilerPassFileCollection;

interface CompilerPassFileFinderInterface
{
    public function find(): CompilerPassFileCollection;

    public function getOptions(): Options\CompilerPassFileFinderOptionsInterface;
}
