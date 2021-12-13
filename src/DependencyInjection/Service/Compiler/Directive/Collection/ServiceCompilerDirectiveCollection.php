<?php

declare(strict_types=1);

namespace LDL\DependencyInjection\Service\Compiler\Directive\Collection;

use LDL\DependencyInjection\Service\Compiler\Directive\ServiceCompilerDirectiveInterface;
use LDL\File\Contracts\FileInterface;
use LDL\Type\Collection\AbstractTypedCollection;
use LDL\Validators\InterfaceComplianceValidator;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class ServiceCompilerDirectiveCollection extends AbstractTypedCollection
{
    public function __construct(iterable $items = null)
    {
        $this->getAppendValueValidatorChain()
            ->getChainItems()
            ->append(
                new InterfaceComplianceValidator(ServiceCompilerDirectiveInterface::class)
            )
            ->lock();

        parent::__construct($items);
    }

    public function compile(
        ContainerBuilder $builder,
        FileInterface $file,
        array $definedServices
    ): void {
        /**
         * @var ServiceCompilerDirectiveInterface $directive
         */
        foreach ($this as $directive) {
            $directive->compile($builder, $file, $definedServices);
        }
    }
}
