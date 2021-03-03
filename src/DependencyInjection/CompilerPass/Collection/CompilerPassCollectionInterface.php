<?php declare(strict_types=1);

namespace LDL\DependencyInjection\CompilerPass\Collection;

use LDL\Type\Collection\Interfaces\CollectionInterface;
use LDL\Type\Collection\Interfaces\Validation\HasValueValidatorChainInterface;


interface CompilerPassCollectionInterface extends CollectionInterface, HasValueValidatorChainInterface
{

}