<?php

declare(strict_types=1);

namespace LDL\DependencyInjection\Container\Writer;

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
    public function write(
        ContainerBuilder $container,
        array $options = []
    ): void
    {
        if($this->options->isMockWrite()){
            return;
        }

        $this->test();

        if(!array_key_exists('namespace', $options)){
            $options['namespace'] = 'LDL\\DependencyInjection';
        }

        if(!array_key_exists('class', $options)){
            $options['class'] = 'LDLContainer';
        }

        if(!array_key_exists('format', $options)){
            $options['format'] = 'php';
        }

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

        unset($options['format']);

        $content = $dumper->dump($options);

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