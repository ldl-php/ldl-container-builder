<?php

declare(strict_types=1);

/**
 * Detects duplicate service id's on service file compilation.
 *
 * @TODO Does NOT work within the SAME file, sadly symfony reduces the duplicate service id's into one single value
 * @TODO Perhaps we will have to decorate symfony loaders some how or create our own.
 */

namespace LDL\DependencyInjection\Service\Compiler\Directive;

use LDL\DependencyInjection\Service\Compiler\Directive\Exception\DuplicateServiceIdException;
use LDL\File\Contracts\FileInterface;
use LDL\Type\Collection\Types\String\StringCollection;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DuplicateServiceCompilerDirective implements ServiceCompilerDirectiveInterface
{
    public function compile(
        ContainerBuilder $builder,
        FileInterface $file,
        array $definedServices
    ): void {
        $allServices = new StringCollection(array_keys($builder->getDefinitions()));
        $allServices->removeByValue('service_container');

        foreach ($definedServices as $definedFile => $services) {
            foreach ($services as $s) {
                if ($allServices->hasValue($s)) {
                    throw new DuplicateServiceIdException(sprintf('Service id "%s" was already defined in file "%s", and redefined in file: "%s"', $s, $definedFile, $file->getPath()));
                }
            }
        }
    }
}
