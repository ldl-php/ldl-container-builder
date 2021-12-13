<?php

declare(strict_types=1);

namespace LDL\DependencyInjection\CompilerPass\Finder;

use LDL\DependencyInjection\CompilerPass\File\CompilerPassFileCollection;
use LDL\File\Constants\FileTypeConstants;
use LDL\File\Finder\Adapter\Local\LocalFileFinderAdapter;
use LDL\File\Validator\FileNameValidator;
use LDL\File\Validator\FileTypeValidator;
use LDL\File\Validator\PathValidator;
use LDL\Framework\Base\Collection\CallableCollectionInterface;
use LDL\Framework\Helper\IterableHelper;
use LDL\Validators\Chain\AndValidatorChain;
use LDL\Validators\Chain\OrValidatorChain;
use LDL\Validators\RegexValidator;

class CompilerPassFileFinder implements CompilerPassFileFinderInterface
{
    /**
     * @var Options\CompilerPassFileFinderOptions
     */
    private $options;

    /**
     * @var CallableCollectionInterface
     */
    private $onCompilerPassFileFound;

    /**
     * @var CallableCollectionInterface
     */
    private $onReject;

    /**
     * @var CallableCollectionInterface
     */
    private $onFile;

    public function __construct(
        Options\CompilerPassFileFinderOptions $options,
        CallableCollectionInterface $onCompilerPassFileFound = null,
        CallableCollectionInterface $onReject = null,
        CallableCollectionInterface $onFile = null
    ) {
        $this->onCompilerPassFileFound = $onCompilerPassFileFound;
        $this->onReject = $onReject;
        $this->onFile = $onFile;

        $this->options = $options;
    }

    /**
     * {@inheritdoc}
     */
    public function find(): CompilerPassFileCollection
    {
        $validators = new AndValidatorChain();

        $patternChain = new OrValidatorChain();

        $patternChain->getChainItems()->appendMany(
            IterableHelper::map($this->options->getPatterns(), static function ($pattern) {
                return new RegexValidator($pattern);
            })
        );

        $validators->getChainItems()->appendMany([
            $patternChain,
            new FileTypeValidator([FileTypeConstants::FILE_TYPE_REGULAR]),
        ]);

        if (count($this->options->getExcludedDirectories()) > 0) {
            foreach ($this->options->getExcludedDirectories() as $dir) {
                $validators->getChainItems()->append(new PathValidator($dir, true));
            }
        }

        if (count($this->options->getExcludedFiles()) > 0) {
            foreach ($this->options->getExcludedFiles() as $file) {
                $validators->getChainItems()->append(new FileNameValidator($file, true));
            }
        }

        $finder = new LocalFileFinderAdapter(
            $validators,
            $this->onCompilerPassFileFound,
            $this->onReject,
            $this->onFile
        );

        return new CompilerPassFileCollection(
            $finder->find(
                $this->options->getDirectories(),
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions(): Options\CompilerPassFileFinderOptions
    {
        return $this->options;
    }
}
