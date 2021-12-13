<?php declare(strict_types=1);

namespace LDL\DependencyInjection\CompilerPass\File;

use LDL\DependencyInjection\CompilerPass\File\Validator\CompilerPassFileValidator;
use LDL\File\Contracts\FileInterface;
use LDL\File\Validator\FileExistsValidator;
use LDL\File\Validator\ReadableFileValidator;
use LDL\Framework\Helper\ReflectionHelper;
use LDL\Type\Collection\AbstractTypedCollection;

class CompilerPassFileCollection extends AbstractTypedCollection
{

    public function __construct(iterable $items = null)
    {
        $this->getAppendValueValidatorChain()
            ->getChainItems()
            ->appendMany([
                new FileExistsValidator(),
                new ReadableFileValidator(),
                new CompilerPassFileValidator()
            ])->lock();

        parent::__construct($items);
    }

    public function getCompilerPassInstances() : array
    {
        $return = [];

        /**
         * @var FileInterface $compilerPass
         */
        foreach($this as $compilerPass) {
            $data = ReflectionHelper::fromFile($compilerPass->getPath());

            foreach ($data as $namespace => $types) {
                if (count($types['class']) === 0) {
                    continue;
                }

                foreach ($types['class'] as $class) {
                    $class = sprintf('%s\\%s', $namespace, $class);
                    $return[] = new $class;
                }
            }
        }

        return $return;
    }

}
