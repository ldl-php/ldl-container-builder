<?php

namespace LDL\Service\Writer;

class ServiceFileWriter implements ServiceFileWriterInterface
{
    /**
     * {@inheritdoc}
     */
    public function write(string $content, Options\ServiceWriterOptions $options=null): void
    {
        $options = $options ?? new Options\ServiceWriterOptions();

        if(false === $options->isForce() && true === file_exists($options->getFilename())){
            $msg = sprintf(
                'File: %s already exists!. Force it to overwrite',
                $options->getFilename()
            );

            throw new Exception\FileAlreadyExistsException($msg);
        }

        file_put_contents($options->getFilename(), $content);
    }
}