<?php declare(strict_types=1);

namespace LDL\DependencyInjection\Interfaces;

use LDL\Framework\Base\Contracts\Type\ToArrayInterface;

interface OptionsInterface extends ToArrayInterface, \JsonSerializable
{

}