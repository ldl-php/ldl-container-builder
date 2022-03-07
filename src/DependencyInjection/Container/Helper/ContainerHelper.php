<?php

declare(strict_types=1);

namespace LDL\DependencyInjection\Container\Helper;

use LDL\File\Contracts\FileInterface;
use LDL\File\File;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\DependencyInjection\Dumper\XmlDumper;
use Symfony\Component\DependencyInjection\Dumper\YamlDumper;

final class ContainerHelper
{
    /**
     * @throws \LDL\File\Exception\FileExistsException
     * @throws \LDL\File\Exception\FileTypeException
     * @throws \LDL\File\Exception\FileWriteException
     */
    public static function write(
        ContainerBuilder $builder,
        string $file,
        array $options = []
    ): FileInterface {
        $extension = substr($file, strrpos($file, '.') + 1);

        switch (strtolower($extension)) {
            case 'xml':
                $dumper = new XmlDumper($builder);
                break;

            case 'yml':
                $dumper = new YamlDumper($builder);
                break;

            default:
                $dumper = new PhpDumper($builder);
                break;
        }

        return File::create($file, $dumper->dump($options), 0644, true);
    }
}
