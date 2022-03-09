<?php

declare(strict_types=1);

namespace LDL\DependencyInjection\CompilerPass\Finder\Options;

use LDL\DependencyInjection\Interfaces\OptionsInterface;
use LDL\DependencyInjection\Interfaces\WriteOptionsInterface;
use LDL\Framework\Base\Contracts\JsonFactoryInterface;
use LDL\Framework\Base\Contracts\JsonFileFactoryInterface;

interface CompilerPassFileFinderOptionsInterface extends OptionsInterface, WriteOptionsInterface, JsonFileFactoryInterface, JsonFactoryInterface, \JsonSerializable
{
}
