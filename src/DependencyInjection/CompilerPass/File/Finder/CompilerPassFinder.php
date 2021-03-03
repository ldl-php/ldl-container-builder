<?php declare(strict_types=1);

namespace LDL\DependencyInjection\CompilerPass\Finder;

use LDL\DependencyInjection\CompilerPass\Collection\CompilerPassCollectionInterface;
use LDL\FS\Finder\Adapter\LocalFileFinder;
use LDL\FS\Type\FileCollection;
use Symfony\Component\String\UnicodeString;

class CompilerPassFinder implements CompilerPassFinderInterface
{
    /**
     * @var Options\CompilerPassFinderOptions
     */
    private $options;

    /**
     * @var FileCollection
     */
    private $files;

    public function __construct(Options\CompilerPassFinderOptions $options = null)
    {
        $this->options = $options ?? Options\CompilerPassFinderOptions::fromArray([]);
        $this->files = new FileCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function find(bool $cache = false): CompilerPassCollectionInterface
    {
        if(true === $cache){
            return $this->files;
        }

        $return = new FileCollection();
        $patterns = $this->options->getPatterns();

        foreach($patterns as $pattern) {

            $files = LocalFileFinder::findRegex(
                $pattern,
                $this->options->getDirectories()
            );

            /**
             * @var \SplFileInfo $file
             */
            foreach ($files as $key => $file) {
                $skip = false;

                if (in_array($file->getRealPath(), $this->options->getExcludedFiles(), true)) {
                    $skip = true;
                }

                foreach ($this->options->getExcludedDirectories() as $directory) {
                    $path = new UnicodeString($file->getPath());
                    $dir = new UnicodeString($directory);

                    if (true === $path->startsWith($dir)) {
                        $skip = true;
                        break;
                    }
                }

                if (true === $skip) {
                    continue;
                }

                $return->append($file);
            }
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