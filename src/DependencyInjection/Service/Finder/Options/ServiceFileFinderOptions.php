<?php

declare(strict_types=1);

namespace LDL\DependencyInjection\Service\Finder\Options;

use LDL\DependencyInjection\Interfaces\OptionsInterface;

class ServiceFileFinderOptions implements OptionsInterface
{
    /**
     * @var array
     */
    private $directories = [];

    /**
     * @var array
     */
    private $findFirst = [];

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
    private $files = [
        'services.xml',
        'services.yml',
        'services.php',
        'services.ini'
    ];

    private function __construct()
    {
    }

    public static function fromArray(array $options) : self
    {
        $instance = new static();
        $defaults = get_object_vars($instance);
        $merge = array_merge($defaults, $options);

        return $instance->setDirectories($merge['directories'])
            ->setFiles($merge['files'])
            ->setFindFirst($merge['findFirst'])
            ->setExcludedDirectories($merge['excludedDirectories'])
            ->setExcludedFiles($merge['excludedFiles']);
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
    public function getExcludedDirectories() : array
    {
        return $this->excludedDirectories;
    }

    /**
     * @return array
     */
    public function getExcludedFiles() : array
    {
        return $this->excludedFiles;
    }

    /**
     * @return array
     */
    public function getFindFirst() : array
    {
        return $this->findFirst;
    }

    /**
     * @return array
     */
    public function getFiles(): array
    {
        return $this->files;
    }

    /**
     * @param array $files
     * @return ServiceFileFinderOptions
     * @throws Exception\InvalidOptionException
     */
    private function setFiles(array $files): ServiceFileFinderOptions
    {
        if(0 === count($files)){
            throw new Exception\InvalidOptionException('No files to find were given');
        }

        $this->files = $files;
        return $this;
    }

    private function setFindFirst(array $files) : ServiceFileFinderOptions
    {
        $this->findFirst = $files;
        return $this;
    }

    /**
     * @return array
     */
    public function getDirectories(): array
    {
        return $this->directories;
    }

    /**
     * @param array $directories
     * @return ServiceFileFinderOptions
     */
    private function setDirectories(array $directories): ServiceFileFinderOptions
    {
        if(0 === count($directories)){
            $directories[] = \getcwd();
        }

        $this->directories = $directories;
        return $this;
    }

    /**
     * @param array $directories
     * @return ServiceFileFinderOptions
     */
    private function setExcludedDirectories(array $directories) : ServiceFileFinderOptions
    {
        $this->excludedDirectories = $directories;
        return $this;
    }

    /**
     * @param array $excludedFiles
     * @return ServiceFileFinderOptions
     */
    private function setExcludedFiles(array $excludedFiles) : ServiceFileFinderOptions
    {
        $this->excludedFiles = $excludedFiles;
        return $this;
    }
}