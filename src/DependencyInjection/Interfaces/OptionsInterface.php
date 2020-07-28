<?php

declare(strict_types=1);

namespace LDL\DependencyInjection\Interfaces;

interface OptionsInterface extends \JsonSerializable
{
    /**
     * @return array
     */
    public function toArray(): array;
}