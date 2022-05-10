<?php

declare(strict_types=1);

namespace LDL\DependencyInjection\Service\File\Finder\Options;

use LDL\File\Collection\Contracts\DirectoryCollectionInterface;
use LDL\File\Collection\DirectoryCollection;
use LDL\File\Contracts\FileInterface;
use LDL\File\Exception\FileExistsException;
use LDL\File\File;
use LDL\Framework\Base\Exception\JsonFactoryException;
use LDL\Type\Collection\Interfaces\Type\StringCollectionInterface;
use LDL\Type\Collection\Types\String\StringCollection;

class ServiceFileFinderOptions implements ServiceFileFinderOptionsInterface
{
    /**
     * @var DirectoryCollection
     */
    private $directories = [];

    /**
     * @var StringCollection
     */
    private $excludedDirectories = [];

    /**
     * @var StringCollection
     */
    private $excludedFiles = [];

    /**
     * @var StringCollection
     */
    private $files = [
        'services.xml',
        'services.yml',
        'services.php',
        'services.ini',
    ];

    public function __construct(
        DirectoryCollectionInterface $directories = null,
        StringCollectionInterface $files = null,
        StringCollectionInterface $excludedDirectories = null,
        StringCollectionInterface $excludedFiles = null
    ) {
        $this->directories = $directories ?? new DirectoryCollection();
        $this->files = ($files ?? new StringCollection())->filterEmptyLines();
        $this->excludedDirectories = ($excludedDirectories ?? new StringCollection())->filterEmptyLines();
        $this->excludedFiles = ($excludedFiles ?? new StringCollection())->filterEmptyLines();
    }

    public static function fromArray(array $options): self
    {
        $defaults = get_class_vars(__CLASS__);
        $merge = array_merge($defaults, $options);

        return new self(
          null === $merge['directories'] ? null : new DirectoryCollection($merge['directories']),
          null === $merge['files'] ? null : new StringCollection($merge['files']),
          null === $merge['excludedDirectories'] ? null : new StringCollection($merge['excludedDirectories']),
          null === $merge['excludedFiles'] ? null : new StringCollection($merge['excludedFiles'])
        );
    }

    /**
     * @throws FileExistsException
     */
    public function toArray(bool $userKeys = null): array
    {
        return [
            'directories' => iterator_to_array($this->directories->getRealPaths()),
            'files' => $this->files->toPrimitiveArray(false),
            'excludedFiles' => $this->excludedFiles->toPrimitiveArray(false),
            'excludedDirectories' => $this->excludedDirectories->toPrimitiveArray(false),
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function getDirectories(): DirectoryCollectionInterface
    {
        return $this->directories;
    }

    public function getExcludedDirectories(): StringCollectionInterface
    {
        return $this->excludedDirectories;
    }

    public function getExcludedFiles(): StringCollectionInterface
    {
        return $this->excludedFiles;
    }

    public function getFiles(): StringCollectionInterface
    {
        return $this->files;
    }

    public function write(string $path, bool $overwrite = true): FileInterface
    {
        return File::create(
            $path,
            json_encode($this, \JSON_THROW_ON_ERROR | \JSON_PRETTY_PRINT),
            0644,
            $overwrite
        );
    }

    public static function fromJsonFile(string $file): ServiceFileFinderOptions
    {
        try {
            return self::fromJsonString((new File($file))->getLinesAsString());
        } catch (\Throwable $e) {
            throw new JsonFactoryException("Could not create instance from file $file");
        }
    }

    public static function fromJsonString(string $json): ServiceFileFinderOptions
    {
        try {
            return self::fromArray(json_decode($json, true, 2048, \JSON_THROW_ON_ERROR));
        } catch (\Throwable $e) {
            throw new JsonFactoryException("Could not create instance from json string: $json");
        }
    }
}
