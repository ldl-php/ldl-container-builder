<?php

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

        file_put_contents($this->options->getFilename(), $content);
    }
}