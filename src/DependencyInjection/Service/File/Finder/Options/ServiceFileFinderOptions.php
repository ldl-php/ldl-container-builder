<?php

declare(strict_types=1);

namespace LDL\DependencyInjection\Service\File\Finder\Options;

use LDL\DependencyInjection\Interfaces\JSONOptionsInterface;
use LDL\DependencyInjection\Interfaces\OptionsInterface;
use LDL\DependencyInjection\Interfaces\WriteOptionsInterface;
use LDL\File\Collection\DirectoryCollection;
use LDL\File\Contracts\DirectoryInterface;
use LDL\File\Contracts\FileInterface;
use LDL\File\File;
use LDL\Framework\Helper\IterableHelper;
use LDL\Type\Collection\Types\String\StringCollection;

class ServiceFileFinderOptions implements OptionsInterface, WriteOptionsInterface, JSONOptionsInterface
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

    public function toArray(bool $userKeys = null): array
    {
        return [
            'directories' => IterableHelper::map($this->directories, static function (DirectoryInterface $d): string {
                return $d->getPath();
            }),
            'files' => $this->files->toPrimitiveArray(false),
            'excludedFiles' => $this->excludedFiles->toPrimitiveArray(false),
            'excludedDirectories' => $this->excludedDirectories->toPrimitiveArray(false),
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function getDirectories(): DirectoryCollection
    {
        return $this->directories;
    }

    public function getExcludedDirectories(): StringCollection
    {
        return $this->excludedDirectories;
    }

    public function getExcludedFiles(): StringCollection
    {
        return $this->excludedFiles;
    }

    public function getFiles(): StringCollection
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

    public static function fromJSONFile(string $file): ServiceFileFinderOptions
    {
        return self::fromJSON((new File($file))->getLinesAsString());
    }

    public static function fromJSON(string $json): ServiceFileFinderOptions
    {
        return self::fromArray(json_decode($json, true, 2048, \JSON_THROW_ON_ERROR));
    }
}
