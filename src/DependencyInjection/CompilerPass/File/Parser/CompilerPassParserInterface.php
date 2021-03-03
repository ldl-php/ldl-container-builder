<?php declare(strict_types=1);

namespace LDL\DependencyInjection\CompilerPass\Parser;

use Symfony\Component\DependencyInjection\ContainerBuilder;

interface CompilerPassParserInterface
{
    /**
     * @param ContainerBuilder $container
     * @param \SplFileInfo $file
     * @return mixed
     */
    public function parse(ContainerBuilder $container, \SplFileInfo $file) : void;

    /**
     * @return Options\CompilerPassReaderOptions
     */
    public function getOptions(): Options\CompilerPassReaderOptions;
}