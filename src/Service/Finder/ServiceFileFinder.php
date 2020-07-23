<?php

namespace LDL\Service\Finder;

use LDL\FS\Finder\Adapter\LocalFileFinder;
use LDL\FS\Type\Types\Generic\Collection\GenericFileCollection;

class ServiceFileFinder implements ServiceFileFinderInterface
{
    /**
     * {@inheritdoc}
     */
    public function find(Options\ServiceFileFinderOptions $options=null) : GenericFileCollection
    {
        $options =  $options ?? Options\ServiceFileFinderOptions::fromArray([]);

        $files = LocalFileFinder::find($options->getDirectories(), $options->getFiles(), true);

        if(!count($files)){
            $msg = sprintf(
                'No files were found matching: "%s" in directories: "%s"',
                implode(', ', $options->getFiles()),
                implode(', ', $options->getDirectories())
            );

            throw new Exception\NoFilesFoundException($msg);
        }

        return $files;
    }

}