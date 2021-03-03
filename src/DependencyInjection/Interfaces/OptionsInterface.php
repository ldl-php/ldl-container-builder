<?php declare(strict_types=1);

namespace LDL\DependencyInjection\Interfaces;

use LDL\Framework\Base\Contracts\ArrayFactoryInterface;
use LDL\Framework\Base\Contracts\ToArrayInterface;

interface OptionsInterface extends \JsonSerializable, ArrayFactoryInterface, ToArrayInterface
{
    /**
     * @return array
     */
    public function toArray(): array;
}