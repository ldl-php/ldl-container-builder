<?php

declare(strict_types=1);

namespace LDL\DependencyInjection\Service\Helper;

use LDL\File\Contracts\FileInterface;
use LDL\File\File;
use LDL\Framework\Base\Exception\InvalidArgumentException;
use LDL\Type\Collection\Types\String\StringCollection;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\FileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\IniFileLoader;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

final class ServiceFileHelper
{
    /**
     * @TODO Allow additional loaders not listed here, for this we would create a LoaderCollection, and add a unique key
     * (which would be the file extension) example 'xml' => XmlFileLoader
     *
     * @throws InvalidArgumentException
     */
    public static function getLoaderByExtension(
        FileInterface $file,
        ContainerBuilder $builder
    ): FileLoader {
        $locator = new FileLocator($file->getPath());
        $extension = strtolower($file->getExtension());

        switch ($extension) {
            case 'xml':
                $loader = new XmlFileLoader($builder, $locator);
                break;

            case 'yml':
                $loader = new YamlFileLoader($builder, $locator);
                break;

            case 'php':
                $loader = new PhpFileLoader($builder, $locator);
                break;

            case 'ini':
                $loader = new IniFileLoader($builder, $locator);
                break;

            default:
                throw new InvalidArgumentException("Unknown file extension $extension");
        }

        return $loader;
    }

    public static function getDefinedServicesInFile(FileInterface $file) : StringCollection
    {
        $builder = new ContainerBuilder();
        $loader = self::getLoaderByExtension($file, $builder);
        $loader->load($file);

        $definitions = $builder->getDefinitions();

        unset($definitions['service_container']);

        return new StringCollection(array_keys($definitions));
    }
}
