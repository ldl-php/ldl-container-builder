<?php

declare(strict_types=1);

namespace LDL\DependencyInjection\Container\Dumper;

use LDL\DependencyInjection\Container\Options\ContainerDumpOptions;
use LDL\DependencyInjection\Container\Options\ContainerDumpOptionsInterface;
use LDL\File\Contracts\FileInterface;
use LDL\Framework\Base\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Dumper\GraphvizDumper;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\DependencyInjection\Dumper\XmlDumper;
use Symfony\Component\DependencyInjection\Dumper\YamlDumper;

class LDLContainerDumper implements LDLContainerDumperInterface
{
    public const DUMP_FORMAT_GRAPHVIZ = 'graphviz';
    public const DUMP_FORMAT_YAML = 'yaml';
    public const DUMP_FORMAT_XML = 'xml';
    public const DUMP_FORMAT_PHP = 'php';

    public static function dump(
        string $format,
        ContainerBuilder $container,
        ContainerDumpOptionsInterface $options = null,
        FileInterface $file = null
    ): string {
        $options = $options ?? new ContainerDumpOptions();
        switch ($format) {
            case self::DUMP_FORMAT_PHP:
                $dumper = new PhpDumper($container);
            break;

            case self::DUMP_FORMAT_XML:
                $dumper = new XmlDumper($container);
            break;

            case self::DUMP_FORMAT_YAML:
                $dumper = new YamlDumper($container);
            break;

            case self::DUMP_FORMAT_GRAPHVIZ:
                $dumper = new GraphvizDumper($container);
            break;

            default:
                $msg = "Invalid dump format: $format";
                throw new InvalidArgumentException($msg);
            break;
        }

        $return = $dumper->dump($options->toArray());

        if (null !== $file) {
            $file->put($return);
        }

        return $return;
    }
}
