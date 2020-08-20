<?php declare(strict_types=1);

namespace LDL\DependencyInjection\Service\Finder;

use LDL\FS\Finder\Adapter\LocalFileFinder;
use LDL\FS\Type\Types\Generic\Collection\GenericFileCollection;
use LDL\FS\Type\Types\Generic\GenericFileType;
use Symfony\Component\String\UnicodeString;

class ServiceFileFinder implements ServiceFileFinderInterface
{
    /**
     * @var Options\ServiceFileFinderOptions
     */
    private $options;

    /**
     * @var GenericFileCollection
     */
    private $files;

    public function __construct(Options\ServiceFileFinderOptions $options=null)
    {
        $this->options = $options ??  Options\ServiceFileFinderOptions::fromArray([]);
        $this->files = new GenericFileCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function find(bool $cache = false) : GenericFileCollection
    {
        if(true === $cache){
            return $this->files;
        }

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
            $skip = false;

            if(in_array($file->getPath(), $this->options->getExcludedFiles(), true)){
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