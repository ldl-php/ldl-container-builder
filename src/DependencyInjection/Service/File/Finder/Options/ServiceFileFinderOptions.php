<?php

declare(strict_types=1);

namespace LDL\DependencyInjection\Service\File\Finder\Options;

use LDL\DependencyInjection\Interfaces\OptionsInterface;
use LDL\File\Collection\DirectoryCollection;
use LDL\Type\Collection\Types\String\StringCollection;

class ServiceFileFinderOptions implements OptionsInterface
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
        return get_object_vars($this);
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
}
