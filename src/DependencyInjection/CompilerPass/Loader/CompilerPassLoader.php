<?php declare(strict_types=1);

namespace LDL\DependencyInjection\CompilerPass;

use LDL\DependencyInjection\CompilerPass\Collection\CompilerPassCollectionInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class CompilerPassLoader implements CompilerPassLoaderInterface{

    public function load(ContainerBuilder $builder, CompilerPassCollectionInterface $passes) : void
    {

    }

}