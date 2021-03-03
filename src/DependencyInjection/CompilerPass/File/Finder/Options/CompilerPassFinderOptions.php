<?php declare(strict_types=1);

namespace LDL\DependencyInjection\CompilerPass\Finder\Options;

use LDL\DependencyInjection\Interfaces\OptionsInterface;
use LDL\Framework\Base\Contracts\ArrayFactoryInterface;

class CompilerPassFinderOptions implements OptionsInterface
{
    public const DEFAULT_PASS_PATTERNS = [
        '^.*CompilerPass.php$'
    ];

    /**
     * @var array
     */
    private $directories = [];

    /**
     * @var array
     */
    private $excludedDirectories = [];

    /**
     * @var array
     */
    private $excludedFiles = [];

    /**
     * @var array
     */
    private $patterns = self::DEFAULT_PASS_PATTERNS;

    public static function fromArray(array $options=[]) : ArrayFactoryInterface
    {
        $instance = new self();
        $defaults = get_object_vars($instance);
        $merge = array_merge($defaults, $options);

        return $instance->setDirectories($merge['directories'])
            ->setExcludedDirectories($merge['excludedDirectories'])
            ->setExcludedFiles($merge['excludedFiles'])
            ->setPatterns($merge['patterns']);
    }

    /**
     * @return array
     */
    public function toArray() : array
    {
        return get_object_vars($this);
    }

    /**
     * @return array
     */
    public function jsonSerialize() : array
    {
        return $this->toArray();
    }

    /**
     * @return array
     */
    public function getDirectories(): array
    {
        return $this->directories;
    }

    /**
     * @return array
     */
    public function getExcludedDirectories() : array
    {
        return $this->excludedDirectories;
    }

    /**
     * @return array
     */
    public function getExcludedFiles(): array
    {
        return $this->excludedFiles;
    }

    /**
     * @param array $directories
     * @return CompilerPassFinderOptions
     */
    private function setDirectories(array $directories): CompilerPassFinderOptions
    {
        if(0 === count($directories)){
            $directories[] = \getcwd();
        }

        $this->directories = $directories;
        return $this;
    }

    /**
     * @return array
     */
    public function getPatterns(): array
    {
        return $this->patterns;
    }

    /**
     * @param array $patterns
     * @return CompilerPassFinderOptions
     */
    private function setPatterns(array $patterns): CompilerPassFinderOptions
    {
        $this->patterns = $patterns;
        return $this;
    }

    /**
     * @param array $directories
     * @return CompilerPassFinderOptions
     */
    private function setExcludedDirectories(array $directories) : CompilerPassFinderOptions
    {
        $this->excludedDirectories = $directories;
        return $this;
    }

    /**
     * @param array $files
     * @return CompilerPassFinderOptions
     */
    public function setExcludedFiles(array $files) : CompilerPassFinderOptions
    {
        $this->excludedFiles = $files;
        return $this;
    }
}