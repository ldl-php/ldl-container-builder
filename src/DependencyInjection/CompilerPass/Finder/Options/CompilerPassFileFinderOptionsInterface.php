<?php

declare(strict_types=1);

namespace LDL\DependencyInjection\CompilerPass\Finder\Options;

use LDL\DependencyInjection\Interfaces\OptionsInterface;
use LDL\DependencyInjection\Interfaces\WriteOptionsInterface;
use LDL\File\Collection\Contracts\DirectoryCollectionInterface;
use LDL\Framework\Base\Contracts\JsonFactoryInterface;
use LDL\Framework\Base\Contracts\JsonFileFactoryInterface;
use LDL\Type\Collection\Interfaces\Type\StringCollectionInterface;

interface CompilerPassFileFinderOptionsInterface extends OptionsInterface, WriteOptionsInterface, JsonFileFactoryInterface, JsonFactoryInterface, \JsonSerializable
{
    public function getDirectories(): DirectoryCollectionInterface;

    public function getExcludedDirectories(): StringCollectionInterface;

    public function getExcludedFiles(): StringCollectionInterface;

    public function getPatterns(): StringCollectionInterface;
}
