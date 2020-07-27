<?php

declare(strict_types=1);

namespace LDL\DependencyInjection\Container\Writer;

class ContainerFileWriter implements ContainerFileWriterInterface
{
    /**
     * @var Options\ContainerWriterOptions
     */
    private $options;

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

    private function test()
    {
        if(!$this->options->isMockWrite() && !is_writable($this->options->getFilename())){
            $msg = sprintf(
                'File: %s is not writable.',
                $this->options->getFilename()
            );

            throw new Exception\FileIsNotWritableException($msg);
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
    }
}