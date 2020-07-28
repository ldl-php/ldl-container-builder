<?php

declare(strict_types=1);

namespace LDL\DependencyInjection\Service\Finder;

use LDL\FS\Finder\Adapter\LocalFileFinder;
use LDL\FS\Type\Types\Generic\Collection\GenericFileCollection;
use LDL\FS\Type\Types\Generic\GenericFileType;

class ServiceFileFinder implements ServiceFileFinderInterface
{
    /**
     * @var Options\ServiceFileFinderOptions
     */
    private $options;

    public function __construct(Options\ServiceFileFinderOptions $options=null)
    {
        $this->options = $options ??  Options\ServiceFileFinderOptions::fromArray([]);
    }

    /**
     * {@inheritdoc}
     */
    public function find() : GenericFileCollection
    {
        $return = new GenericFileCollection();

        $options =  $this->options;

        foreach($options->getFindFirst() as $first){
            $return->append(new GenericFileType($first));
        }

        $files = LocalFileFinder::find(
            $this->options->getDirectories(),
            $this->options->getFiles()
        );

        /**
         * @var GenericFileType $file
         */
        foreach($files as $key=>$file){
            if(in_array($file->getPath(), $this->options->getExcludedDirectories(), true)){
                unset($files[$key]);
                continue;
            }

            if(in_array($file->getRealPath(), $this->options->getExcludedFiles(), true)){
                unset($files[$key]);
                continue;
            }

            $return->append($file);
        }

        if(!count($return)){
            $msg = sprintf(
                'No files were found matching: "%s" in directories: "%s"',
                implode(', ', $options->getFiles()),
                implode(', ', $options->getDirectories())
            );

            throw new Exception\NoFilesFoundException($msg);
        }

        return $return;
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions(): Options\ServiceFileFinderOptions
    {
        return $this->options;
    }

}