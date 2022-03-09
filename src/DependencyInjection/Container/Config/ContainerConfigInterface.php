<?php

declare(strict_types=1);

namespace LDL\DependencyInjection\Container\Config;

use LDL\DependencyInjection\Interfaces\OptionsInterface;
use LDL\DependencyInjection\Interfaces\WriteOptionsInterface;
use LDL\Framework\Base\Contracts\JsonFactoryInterface;
use LDL\Framework\Base\Contracts\JsonFileFactoryInterface;

interface ContainerConfigInterface extends OptionsInterface, WriteOptionsInterface, JsonFileFactoryInterface, JsonFactoryInterface
{
}
