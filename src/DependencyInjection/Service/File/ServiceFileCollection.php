<?php

declare(strict_types=1);

namespace LDL\DependencyInjection\Service\File;

use LDL\DependencyInjection\Service\File\Validator\ServiceFileValidator;
use LDL\File\Contracts\FileInterface;
use LDL\File\File;
use LDL\File\Validator\FileExistsValidator;
use LDL\File\Validator\ReadableFileValidator;
use LDL\Framework\Base\Collection\Contracts\CollectionInterface;
use LDL\Type\Collection\AbstractTypedCollection;

class ServiceFileCollection extends AbstractTypedCollection
{
    public function __construct(iterable $items = null)
    {
        $this->getAppendValueValidatorChain()
            ->getChainItems()
            ->appendMany([
                new FileExistsValidator(),
                new ReadableFileValidator(),
                new ServiceFileValidator(),
            ])->lock();

        parent::__construct($items);
    }

    public function append($item, $key = null): CollectionInterface
    {
        return parent::append(($item instanceof FileInterface) ? $item : new File($item), $key);
    }
}
