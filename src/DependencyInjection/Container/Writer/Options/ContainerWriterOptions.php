<?php

declare(strict_types=1);

namespace LDL\DependencyInjection\Container\Writer\Options;

use LDL\DependencyInjection\Interfaces\OptionsInterface;

class ContainerWriterOptions implements OptionsInterface
{
    /**
     * @var string
     */
    private $filename = 'container';

    /**
     * @var bool
     */
    private $mockWrite = false;

    /**
     * @var bool
     */
    private $force = false;

    public static function fromArray(array $options) : self
    {
        $instance = new static();
        $defaults = get_object_vars($instance);

        foreach($options as $opt=>$value){
            if(array_key_exists($opt, $defaults)) {
                continue;
            }
            $msg = sprintf(
                'Unknown option: "%s", valid options are: %s',
                $opt,
                implode(', ', array_keys($defaults))
            );

            throw new Exception\UnknownOptionException($msg);
        }

        $merge = array_merge($defaults, $options);

        return $instance->setFilename($merge['filename'])
            ->setForce($merge['force'])
            ->setMockWrite($merge['mockWrite']);
    }

    /**
     * @return array
     */
    public function toArray() : array
    {
        return get_object_vars($this);
    }

    /**
     * @return array
     */
    public function jsonSerialize() : array
    {
        return $this->toArray();
    }

    /**
     * @return string
     */
    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * @param string $filename
     * @return ContainerWriterOptions
     */
    private function setFilename(string $filename): ContainerWriterOptions
    {
        $this->filename = $filename;
        return $this;
    }
    /**
     * @return bool
     */
    public function isForce(): bool
    {
        return $this->force;
    }

    /**
     * @return bool
     */
    public function isMockWrite() : bool
    {
        return $this->mockWrite;
    }

    /**
     * @param bool $force
     * @return ContainerWriterOptions
     */
    private function setForce(bool $force): ContainerWriterOptions
    {
        $this->force = $force;
        return $this;
    }

    /**
     * @param bool $value
     * @return ContainerWriterOptions
     */
    private function setMockWrite(bool $value) : ContainerWriterOptions
    {
        $this->mockWrite = $value;
        return $this;
    }

}