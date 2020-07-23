<?php

namespace LDL\Service\Reader;

use LDL\FS\Type\AbstractFileType;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class ServiceReader implements ServiceReaderInterface
{
    public function read(ContainerBuilder $container, Options\ServiceReaderOptions $options)
    {
        /**
         * @var AbstractFileType
         */
        $file = $options->getFile();

        $locator = new FileLocator($file->getPath());

        switch($file->getExtension()){
            case 'xml':
                $loader = new XmlFileLoader($container, $locator);
                break;
            case 'yml':
                $loader = new YamlFileLoader($container, $locator);
                break;
            case 'php':
                $loader = new PhpFileLoader($container, $locator);
                break;
        }

        $loader->load($file);
    }

}