<?php

declare(strict_types=1);

namespace LDL\DependencyInjection\Service\File\Finder\Options;

use LDL\DependencyInjection\Interfaces\OptionsInterface;
use LDL\DependencyInjection\Interfaces\WriteOptionsInterface;
use LDL\Framework\Base\Contracts\JsonFactoryInterface;
use LDL\Framework\Base\Contracts\JsonFileFactoryInterface;

interface ServiceFileFinderOptionsInterface extends OptionsInterface, WriteOptionsInterface, JsonFactoryInterface, JsonFileFactoryInterface, \JsonSerializable
{
}
