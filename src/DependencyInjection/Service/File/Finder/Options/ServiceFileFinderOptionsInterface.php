<?php

declare(strict_types=1);

namespace LDL\DependencyInjection\Service\File\Finder\Options;

use LDL\DependencyInjection\Interfaces\OptionsInterface;
use LDL\DependencyInjection\Interfaces\WriteOptionsInterface;
use LDL\File\Collection\Contracts\DirectoryCollectionInterface;
use LDL\Framework\Base\Contracts\JsonFactoryInterface;
use LDL\Framework\Base\Contracts\JsonFileFactoryInterface;
use LDL\Type\Collection\Interfaces\Type\StringCollectionInterface;

interface ServiceFileFinderOptionsInterface extends OptionsInterface, WriteOptionsInterface, JsonFactoryInterface, JsonFileFactoryInterface, \JsonSerializable
{
    public function getDirectories(): DirectoryCollectionInterface;

    public function getExcludedDirectories(): StringCollectionInterface;

    public function getExcludedFiles(): StringCollectionInterface;

    public function getFiles(): StringCollectionInterface;
}
