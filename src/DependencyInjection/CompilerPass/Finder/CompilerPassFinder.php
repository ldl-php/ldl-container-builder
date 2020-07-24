<?php

namespace LDL\DependencyInjection\CompilerPass\Finder;

use LDL\FS\Finder\Adapter\LocalFileFinder;
use LDL\FS\Type\Types\Generic\Collection\GenericFileCollection;
use LDL\FS\Type\Types\Generic\GenericFileType;

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

            if(in_array($file->getPath(), $this->options->getExcludedDirectories(), true)){
                unset($files[$key]);
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
}