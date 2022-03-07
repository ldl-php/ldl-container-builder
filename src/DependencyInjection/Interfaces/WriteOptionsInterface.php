<?php

declare(strict_types=1);

namespace LDL\DependencyInjection\Interfaces;

use LDL\File\Contracts\FileInterface;

interface WriteOptionsInterface
{
    public function write(string $path): FileInterface;
}
