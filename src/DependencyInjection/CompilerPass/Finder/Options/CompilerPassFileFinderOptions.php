<?php

declare(strict_types=1);

namespace LDL\DependencyInjection\CompilerPass\Finder\Options;

use LDL\DependencyInjection\Interfaces\JSONOptionsInterface;
use LDL\DependencyInjection\Interfaces\OptionsInterface;
use LDL\DependencyInjection\Interfaces\WriteOptionsInterface;
use LDL\File\Contracts\FileInterface;
use LDL\File\File;
use LDL\Framework\Helper\IterableHelper;
use LDL\Type\Collection\Interfaces\Type\ToPrimitiveArrayInterface;
use LDL\Type\Collection\Types\String\StringCollection;

class CompilerPassFileFinderOptions implements OptionsInterface, WriteOptionsInterface, JSONOptionsInterface
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
        return IterableHelper::map(get_object_vars($this), static function (ToPrimitiveArrayInterface $v, $k): array {
            return $v->toPrimitiveArray(false);
        });
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

    public function write(string $path, bool $overwrite = true): FileInterface
    {
        return File::create(
            $path,
            json_encode($this, \JSON_THROW_ON_ERROR | \JSON_PRETTY_PRINT),
            0644,
            $overwrite
        );
    }

    public static function fromJSONFile(string $file): CompilerPassFileFinderOptions
    {
        return self::fromJSON((new File($file))->getLinesAsString());
    }

    public static function fromJSON(string $json): CompilerPassFileFinderOptions
    {
        return self::fromArray(json_decode($json, true, 2048, \JSON_THROW_ON_ERROR));
    }
}
