<?php

declare(strict_types=1);

namespace LDL\DependencyInjection\Console;

use Symfony\Component\Console\Application as SymfonyApplication;

class ContainerBuilderApplication extends SymfonyApplication
{
    /**
     * Console constructor.
     */
    public function __construct(string $name = 'UNKNOWN', string $version = 'UNKNOWN')
    {
        parent::__construct('<info>[ LDL Container builder ]</info>', $version);
    }
}
