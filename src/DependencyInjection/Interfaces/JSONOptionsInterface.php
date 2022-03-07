<?php

declare(strict_types=1);

namespace LDL\DependencyInjection\Interfaces;

interface JSONOptionsInterface extends \JsonSerializable
{
    public static function fromJSON(string $json);

    public static function fromJSONFile(string $file);
}
