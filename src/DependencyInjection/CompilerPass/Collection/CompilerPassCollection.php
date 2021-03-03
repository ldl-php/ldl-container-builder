<?php declare(strict_types=1);

namespace LDL\DependencyInjection\CompilerPass\Collection;

use LDL\Type\Collection\Types\Object\ObjectCollection;
use LDL\Type\Collection\Types\Object\Validator\InterfaceComplianceItemValidator;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class CompilerPassCollection extends ObjectCollection implements CompilerPassCollectionInterface
{
    public function __construct(iterable $items = null)
    {
        parent::__construct($items);

        $this->getValueValidatorChain()
            ->append(new InterfaceComplianceItemValidator(CompilerPassInterface::class))
            ->lock();
    }

}