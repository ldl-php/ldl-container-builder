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

    private function __construct()
    {
    }

    public static function fromArray(array $options): self
    {
        $instance = new static();
        $defaults = get_object_vars($instance);
        $merge = array_merge($defaults, $options);

        $instance->directories = new DirectoryCollection($merge['directories']);
        $instance->files = (new StringCollection($merge['files']))->filterEmptyLines();
        $instance->excludedDirectories = (new StringCollection($merge['excludedDirectories']))->filterEmptyLines();
        $instance->excludedFiles = (new StringCollection($merge['excludedFiles']))->filterEmptyLines();

        return $instance;
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
