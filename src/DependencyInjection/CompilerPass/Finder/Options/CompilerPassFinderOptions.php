<?php

namespace LDL\DependencyInjection\CompilerPass\Finder\Options;

class CompilerPassFinderOptions
{
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
     * @var string
     */
    private $pattern = '^.*CompilerPass.php$';

    public static function fromArray(array $options) : self
    {
        $instance = new static();
        $defaults = get_object_vars($instance);
        $merge = array_merge($defaults, $options);

        return $instance->setDirectories($merge['directories'])
            ->setExcludedDirectories($merge['excludedDirectories'])
            ->setExcludedFiles($merge['excludedFiles'])
            ->setPattern($merge['pattern']);
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
     * @return string
     */
    public function getPattern(): string
    {
        return $this->pattern;
    }

    /**
     * @param string $pattern
     * @return CompilerPassFinderOptions
     */
    private function setPattern(string $pattern): CompilerPassFinderOptions
    {
        $this->pattern = $pattern;
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