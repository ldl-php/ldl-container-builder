<?php

declare(strict_types=1);

namespace LDL\DependencyInjection\CompilerPass\Finder\Options;

use LDL\DependencyInjection\Interfaces\OptionsInterface;
use LDL\Type\Collection\Types\String\StringCollection;

class CompilerPassFileFinderOptions implements OptionsInterface
{
    public const DEFAULT_FILE_PATTERN = '#^.*CompilerPass.php$#';

    /**
     * @var StringCollection
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

    public static function fromArray(array $options): self
    {
        $instance = new static();
        $defaults = get_object_vars($instance);
        $merge = array_merge($defaults, $options);

        $instance->directories = new StringCollection($merge['directories']);
        $instance->excludedDirectories = (new StringCollection($merge['excludedDirectories']))->filterEmptyLines();
        $instance->excludedFiles = (new StringCollection($merge['excludedFiles']))->filterEmptyLines();
        $instance->patterns = new StringCollection($merge['patterns']);

        return $instance;
    }

    public function toArray(bool $useKeys = null): array
    {
        return get_object_vars($this);
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function getDirectories(): StringCollection
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

    public function getPatterns(): StringCollection
    {
        return $this->patterns;
    }
}
