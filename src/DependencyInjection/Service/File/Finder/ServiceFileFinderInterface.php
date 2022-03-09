<?php

declare(strict_types=1);

namespace LDL\DependencyInjection\Service\File\Finder;

use LDL\DependencyInjection\Service\File\ServiceFileCollection;

interface ServiceFileFinderInterface
{
    public function find(): ServiceFileCollection;

    public function getOptions(): Options\ServiceFileFinderOptionsInterface;
}
