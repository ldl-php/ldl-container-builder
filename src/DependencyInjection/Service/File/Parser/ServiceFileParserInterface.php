<?php declare(strict_types=1);

namespace LDL\DependencyInjection\Service\File\Parser;

use Symfony\Component\DependencyInjection\ContainerBuilder;

interface ServiceFileParserInterface
{
    /**
     * @param ContainerBuilder $container
     * @param \SplFileInfo $file
     * @return mixed
     */
    public function parse(ContainerBuilder $container, \SplFileInfo $file) : void;

    /**
     * @return Options\ServiceFileParserOptions
     */
    public function getOptions() : Options\ServiceFileParserOptions;
}