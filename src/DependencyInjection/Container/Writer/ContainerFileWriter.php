<?php declare(strict_types=1);

namespace LDL\DependencyInjection\Container\Writer;

use LDL\DependencyInjection\Container\Config\ContainerConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\DependencyInjection\Dumper\XmlDumper;
use Symfony\Component\DependencyInjection\Dumper\YamlDumper;

class ContainerFileWriter implements ContainerFileWriterInterface
{
    /**
     * @var Options\ContainerWriterOptions
     */
    private $options;

    /**
     * @var bool
     */
    private $canWrite = false;

    public function __construct(Options\ContainerWriterOptions $options=null)
    {
        $this->options = $options ?? Options\ContainerWriterOptions::fromArray([]);
    }

    /**
     * {@inheritdoc}
     */
    public function write(ContainerConfig $config, ContainerBuilder $container): void
    {
        $options = $config->getDumpOptions();

        if($this->options->isMockWrite()){
            return;
        }

        $this->test();

        switch(strtolower($options['format'])){
            case 'xml':
                $dumper = new XmlDumper($container);
                break;

            case 'yml':
                $dumper = new YamlDumper($container);
                break;

            case 'php':
            default:
                $dumper = new PhpDumper($container);
                break;
        }

        $content = $dumper->dump($options);

        file_put_contents(
            $config->getGeneratedAs(),
            json_encode($config->toArray(), \JSON_THROW_ON_ERROR | \JSON_PRETTY_PRINT | \JSON_UNESCAPED_SLASHES)
        );

        file_put_contents($this->options->getFilename(), $content);
    }

    /**
     * {@inheritdoc}
     */
    public function test(): void
    {
        if ($this->canWrite || $this->options->isMockWrite()) {
            return;
        }

        $file = $this->options->getFilename();
        $exists = file_exists($file);
        $isWritable = $exists ? $file : dirname($file);
        $isDir = is_dir($file);

        if(
            !$this->options->isMockWrite() &&
            !is_writable($isWritable)
        ){
            $msg = sprintf(
                '%s %s, is not writable',
                $isDir ? 'Directory' : 'File',
                $isWritable
            );

            $exception = $isDir ? 'Exception\DirectoryIsNotWritableException' : 'Exception\FileIsNotWritableException';

            throw new $exception($msg);
        }

        if(
            false === $this->options->isForce() &&
            true === file_exists($this->options->getFilename())
        ){
            $msg = sprintf(
                'File: %s already exists!. Set force option to true to overwrite.',
                $this->options->getFilename()
            );

            throw new Exception\FileAlreadyExistsException($msg);
        }

        $this->canWrite = true;
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions(): Options\ContainerWriterOptions
    {
        return clone($this->options);
    }
}