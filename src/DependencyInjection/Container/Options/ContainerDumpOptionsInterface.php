<?php

declare(strict_types=1);

namespace LDL\DependencyInjection\Container\Options;

use LDL\File\Contracts\FileInterface;
use LDL\Framework\Base\Contracts\ArrayFactoryInterface;
use LDL\Framework\Base\Contracts\JsonFactoryInterface;
use LDL\Framework\Base\Contracts\JsonFileFactoryInterface;
use LDL\Framework\Base\Contracts\Type\ToArrayInterface;
use LDL\Type\Collection\Interfaces\Type\StringCollectionInterface;

interface ContainerDumpOptionsInterface extends ArrayFactoryInterface, ToArrayInterface, \JsonSerializable, JsonFactoryInterface, JsonFileFactoryInterface
{
    public function getNamespace(): ?string;

    public function getBaseClass(): ?string;

    public function getClass(): ?string;

    public function isAsFiles(): ?bool;

    public function isDebug(): ?bool;

    public function getHotPathTag(): ?string;

    public function getPreloadTags(): ?StringCollectionInterface;

    public function getInlineFactoriesParameter(): ?string;

    public function getPreloadClasses(): ?StringCollectionInterface;

    public function getServiceLocatorTag(): ?string;

    public function write(string $path, int $perms, bool $force): FileInterface;
}
