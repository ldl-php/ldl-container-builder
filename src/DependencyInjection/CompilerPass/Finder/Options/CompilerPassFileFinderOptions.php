<?php

declare(strict_types=1);

namespace LDL\DependencyInjection\CompilerPass\Finder\Options;

use LDL\File\Collection\Contracts\DirectoryCollectionInterface;
use LDL\File\Collection\DirectoryCollection;
use LDL\File\Contracts\FileInterface;
use LDL\File\File;
use LDL\Framework\Base\Exception\JsonFactoryException;
use LDL\Type\Collection\Interfaces\Type\StringCollectionInterface;
use LDL\Type\Collection\Types\String\StringCollection;

class CompilerPassFileFinderOptions implements CompilerPassFileFinderOptionsInterface
{
    public const DEFAULT_FILE_PATTERN = '#^.*CompilerPass.php$#';

    /**
     * @var DirectoryCollectionInterface
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
    private $patterns = ['#^.*CompilerPass.php$#'];

    public function __construct(
        DirectoryCollectionInterface $directories = null,
        StringCollectionInterface $excludedDirectories = null,
        StringCollectionInterface $excludedFiles = null,
        StringCollection $patterns = null
    ) {
        $this->directories = $directories ?? new DirectoryCollection();
        $this->excludedDirectories = ($excludedDirectories ?? new StringCollection())->filterEmptyLines();
        $this->excludedFiles = ($excludedFiles ?? new StringCollection())->filterEmptyLines();
        $this->patterns = ($patterns ?? new StringCollection())->filterEmptyLines();
    }

    public static function fromArray(array $options): CompilerPassFileFinderOptionsInterface
    {
        $merge = array_merge(get_class_vars(__CLASS__), $options);

        return new self(
            new DirectoryCollection($merge['directories']),
            new StringCollection($merge['excludedDirectories']),
            new StringCollection($merge['excludedFiles']),
            new StringCollection($merge['patterns'])
        );
    }

    public function toArray(bool $useKeys = null): array
    {
        return [
            'directories' => iterator_to_array($this->directories->getRealPaths()),
            'patterns' => $this->patterns->toPrimitiveArray(false),
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

    public function getPatterns(): StringCollectionInterface
    {
        return $this->patterns;
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

    public static function fromJsonFile(string $file): CompilerPassFileFinderOptionsInterface
    {
        try {
            return self::fromJsonString((new File($file))->getLinesAsString());
        } catch (\Throwable $e) {
            throw new JsonFactoryException("Could not create instance from file $file");
        }
    }

    public static function fromJsonString(string $json): CompilerPassFileFinderOptionsInterface
    {
        try {
            return self::fromArray(json_decode($json, true, 2048, \JSON_THROW_ON_ERROR));
        } catch (\Throwable $e) {
            throw new JsonFactoryException("Could not create instance from json string $json");
        }
    }
}
