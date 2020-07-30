<?php

declare(strict_types=1);

namespace LDL\DependencyInjection\CompilerPass\Finder;

use LDL\FS\Finder\Adapter\LocalFileFinder;
use LDL\FS\Type\Types\Generic\Collection\GenericFileCollection;
use LDL\FS\Type\Types\Generic\GenericFileType;
use Symfony\Component\String\UnicodeString;

class CompilerPassFinder implements CompilerPassFinderInterface
{
    /**
     * @var Options\CompilerPassFinderOptions
     */
    private $options;

    public function __construct(Options\CompilerPassFinderOptions $options = null)
    {
        $this->options = $options ?? Options\CompilerPassFinderOptions::fromArray([]);
    }

    /**
     * {@inheritdoc}
     */
    public function find(): GenericFileCollection
    {
        $files = LocalFileFinder::findRegex(
            $this->options->getPattern(),
            $this->options->getDirectories()
        );

        /**
         * @var GenericFileType $file
         */
        foreach($files as $key => $file){
            if(in_array($file->getRealPath(), $this->options->getExcludedFiles(), true)){
                unset($files[$key]);
            }

            foreach($this->options->getExcludedDirectories() as $directory){
                $path = new UnicodeString($file->getPath());
                $dir = new UnicodeString($directory);

                if(true === $path->startsWith($dir)){
                    unset($files[$key]);
                }
            }
        }

        if(!count($files)){
            $msg = sprintf(
                'No files were found matching: "%s" in directories: "%s"',
                $this->options->getPattern(),
                implode(', ', $this->options->getDirectories())
            );

            throw new Exception\NoFilesFoundException($msg);
        }

        return $files;
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions() : Options\CompilerPassFinderOptions
    {
        return $this->options;
    }
}