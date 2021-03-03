<?php declare(strict_types=1);

namespace LDL\DependencyInjection\Service\File\Finder;

use LDL\FS\Finder\Adapter\LocalFileFinder;
use LDL\FS\Type\FileCollection;
use Symfony\Component\String\UnicodeString;

class ServiceFileFinder implements ServiceFileFinderInterface
{
    /**
     * @var Options\ServiceFileFinderOptions
     */
    private $options;

    /**
     * @var FileCollection
     */
    private $files;

    public function __construct(Options\ServiceFileFinderOptions $options=null)
    {
        $this->options = $options ??  Options\ServiceFileFinderOptions::fromArray([]);
        $this->files = new FileCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function find(bool $cache = false) : FileCollection
    {
        if(true === $cache){
            return $this->files;
        }

        $return = new FileCollection();

        $options =  $this->options;

        foreach($options->getFindFirst() as $first){
            $return->append(new \SplFileInfo($first));
        }

        $files = LocalFileFinder::find(
            $this->options->getDirectories(),
            $this->options->getFiles()
        );

        /**
         * @var \SplFileInfo $file
         */
        foreach($files as $key=>$file){
            $skip = false;

            if(in_array($file->getPath(), $this->options->getExcludedFiles(), true)){
                continue;
            }

            foreach($this->options->getExcludedDirectories() as $directory){
                $path = new UnicodeString($file->getPath());

                if(true === $path->startsWith($directory)){
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
                implode(', ', $options->getFiles()),
                implode(', ', $options->getDirectories())
            );

            throw new Exception\NoFilesFoundException($msg);
        }

        $this->files = $return;
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