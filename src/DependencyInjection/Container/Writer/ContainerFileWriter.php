<?php

declare(strict_types=1);

namespace LDL\DependencyInjection\Container\Writer;

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
    public function write(string $content): void
    {
        if($this->options->isMockWrite()){
            return;
        }

        $this->test();

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
}