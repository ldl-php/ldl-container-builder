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

    /**
     * @var GenericFileCollection
     */
    private $files;

    public function __construct(Options\CompilerPassFinderOptions $options = null)
    {
        $this->options = $options ?? Options\CompilerPassFinderOptions::fromArray([]);
        $this->files = new GenericFileCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function find(bool $cache = false): GenericFileCollection
    {
        if(true === $cache){
            return $this->files;
        }

        $return = new GenericFileCollection();

        $files = LocalFileFinder::findRegex(
            $this->options->getPattern(),
            $this->options->getDirectories()
        );

        /**
         * @var GenericFileType $file
         */
        foreach($files as $key => $file){
            $skip = false;

            if(in_array($file->getRealPath(), $this->options->getExcludedFiles(), true)){
                $skip = true;
            }

            foreach($this->options->getExcludedDirectories() as $directory){
                $path = new UnicodeString($file->getPath());
                $dir = new UnicodeString($directory);

                if(true === $path->startsWith($dir)){
                    $skip = true;
                    break;
                }
            }

            if(true === $skip){
                continue;
            }

            $return->append($file);
        }

        if(!count($return)){
            $msg = sprintf(
                'No files were found matching: "%s" in directories: "%s"',
                $this->options->getPattern(),
                implode(', ', $this->options->getDirectories())
            );

            throw new Exception\NoFilesFoundException($msg);
        }

        $this->files = $return;
        return $return;
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions() : Options\CompilerPassFinderOptions
    {
        return $this->options;
    }
}