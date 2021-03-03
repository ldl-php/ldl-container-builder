<?php declare(strict_types=1);

namespace LDL\DependencyInjection\Service\File\Parser;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\IniFileLoader;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class ServiceFileParser implements ServiceFileParserInterface
{

    /**
     * @var Options\ServiceFileParserOptions
     */
    private $options;

    public function __construct(Options\ServiceFileParserOptions $options = null)
    {
        $this->options = $options ??  Options\ServiceFileParserOptions::fromArray([]);
    }

    public function parse(ContainerBuilder $container, \SplFileInfo $file) : void
    {
        $locator = new FileLocator($file->getPath());

        if(!$this->options->ignoreErrors() && !$file->isReadable()){
            $msg = sprintf(
                'Could not read file "%s", file is not readable',
                $file->getRealPath()
            );

            throw new Exception\PermissionException($msg);
        }

        $extension = strtolower($file->getExtension());

        try{

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
                case 'ini':
                    $loader = new IniFileLoader($container, $locator);
                    break;

                default:
                    if($this->options->ignoreErrors()){
                        return;
                    }

                    $msg = sprintf(
                        'Could not parse file with extension: %s, file: "%s"',
                        $extension,
                        $file->getRealPath()
                    );

                    throw new Exception\ParserNotFoundException($msg);

                    break;
            }

            $loader->load($file);
        }catch(\Exception $e){
            if($this->options->ignoreErrors()){
                return;
            }

            $msg = sprintf(
                'File "%s" could not be parsed with class: %s',
                $file->getRealPath(),
                get_class($loader)
            );

            throw new Exception\ParserNotFoundException($msg);
        }
    }

    /**
     * @return Options\ServiceFileParserOptions
     */
    public function getOptions() : Options\ServiceFileParserOptions
    {
        return $this->options;
    }

}