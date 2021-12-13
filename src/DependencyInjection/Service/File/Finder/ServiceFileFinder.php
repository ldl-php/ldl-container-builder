<?php

declare(strict_types=1);

namespace LDL\DependencyInjection\Service\File\Finder;

use LDL\DependencyInjection\Service\File\ServiceFileCollection;
use LDL\File\Constants\FileTypeConstants;
use LDL\File\Finder\Adapter\Local\LocalFileFinderAdapter;
use LDL\File\Finder\FoundFile;
use LDL\File\Validator\FileNameValidator;
use LDL\File\Validator\FileTypeValidator;
use LDL\File\Validator\PathValidator;
use LDL\Framework\Base\Collection\CallableCollectionInterface;
use LDL\Type\Collection\Types\String\StringCollection;
use LDL\Validators\Chain\AndValidatorChain;
use LDL\Validators\Chain\OrValidatorChain;

class ServiceFileFinder implements ServiceFileFinderInterface
{
    /**
     * @var Options\ServiceFileFinderOptions
     */
    private $options;

    /**
     * @var CallableCollectionInterface
     */
    private $onServiceFileFound;

    /**
     * @var CallableCollectionInterface
     */
    private $onReject;

    /**
     * @var CallableCollectionInterface
     */
    private $onFile;

    public function __construct(
        Options\ServiceFileFinderOptions $options,
        CallableCollectionInterface $onServiceFileFound = null,
        CallableCollectionInterface $onFile = null,
        CallableCollectionInterface $onReject = null
    ) {
        $this->onServiceFileFound = $onServiceFileFound;
        $this->onFile = $onFile;
        $this->onReject = $onReject;

        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    public function find(): ServiceFileCollection
    {
        $files = new ServiceFileCollection();

        $options = $this->options;

        $validators = new AndValidatorChain([
            new FileTypeValidator([FileTypeConstants::FILE_TYPE_REGULAR]),
        ]);

        if (count($options->getExcludedDirectories()) > 0) {
            foreach ($options->getExcludedDirectories() as $dir) {
                $validators->getChainItems()->append(new PathValidator($dir, true));
            }
        }

        if (count($options->getExcludedFiles()) > 0) {
            foreach ($options->getExcludedFiles() as $file) {
                $validators->getChainItems()->append(new FileNameValidator($file, true));
            }
        }

        $fileValidatorChain = new OrValidatorChain();

        foreach ($this->options->getFiles() as $file) {
            $fileValidatorChain->getChainItems()->append(new FileNameValidator($file));
        }

        $validators->getChainItems()->append($fileValidatorChain);

        $finder = new LocalFileFinderAdapter(
            $validators,
            $this->onServiceFileFound,
            $this->onReject,
            $this->onFile
        );

        $foundFiles = iterator_to_array($finder->find($this->options->getDirectories()), false);

        if (!count($foundFiles)) {
            $msg = sprintf(
                'No files were found matching: "%s" in directories: "%s"',
                $options->getFiles(),
                new StringCollection($options->getDirectories())
            );

            throw new Exception\NoFilesFoundException($msg);
        }

        /**
         * @var FoundFile $foundFile
         */
        foreach ($foundFiles as $foundFile) {
            $files->append($foundFile->getPath());
        }

        return $files;
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions(): Options\ServiceFileFinderOptions
    {
        return $this->options;
    }
}
