<?php

declare(strict_types=1);

namespace LDL\DependencyInjection\Service\Compiler\Options;

use LDL\DependencyInjection\Interfaces\OptionsInterface;

class ServiceCompilerOptions implements OptionsInterface
{

    /**
     * @var string
     */
    private $dumpFormat = 'php';

    /**
     * @var callable
     */
    private $onBeforeCompile;

    /**
     * @var callable
     */
    private $onCompile;

    /**
     * @var callable
     */
    private $onAfterCompile;

    /**
     * @var array
     */
    private $dumpOptions = [];

    private function __construct()
    {
    }

    public static function fromArray(array $options) : self
    {
        $instance = new static();
        $defaults = get_object_vars($instance);
        $merge = array_merge($defaults, $options);

        return $instance->setDumpFormat($merge['dumpFormat'])
            ->setOnBeforeCompile($merge['onBeforeCompile'])
            ->setOnCompile($merge['onCompile'])
            ->setOnAfterCompile($merge['onAfterCompile'])
            ->setDumpOptions($merge['dumpOptions']);
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
    public function getDumpFormat() : string
    {
        return $this->dumpFormat;
    }

    /**
     * @param string $format
     * @return ServiceCompilerOptions
     */
    private function setDumpFormat(string $format) : ServiceCompilerOptions
    {
        $this->dumpFormat = $format;
        return $this;
    }

    /**
     * @return callable|null
     */
    public function getOnBeforeCompile() : ?callable
    {
        return $this->onBeforeCompile;
    }

    /**
     * @return callable|null
     */
    public function getOnCompile() : ?callable
    {
        return $this->onCompile;
    }

    /**
     * @return callable|null
     */
    public function getOnAfterCompile() : ?callable
    {
        return $this->onAfterCompile;
    }

    /**
     * @param callable $fn
     * @return ServiceCompilerOptions
     */
    private function setOnAfterCompile(callable $fn=null) : ServiceCompilerOptions
    {
        $this->onAfterCompile = $fn;
        return $this;
    }

    /**
     * @param callable $fn
     * @return ServiceCompilerOptions
     */
    private function setOnCompile(callable $fn=null) : ServiceCompilerOptions
    {
        $this->onCompile = $fn;
        return $this;
    }

    /**
     * @param callable|null $fn
     * @return ServiceCompilerOptions
     */
    private function setOnBeforeCompile(callable $fn=null) : ServiceCompilerOptions
    {
        $this->onBeforeCompile = $fn;
        return $this;
    }

    /**
     * @return array
     */
    public function getDumpOptions(): array
    {
        return $this->dumpOptions;
    }

    /**
     * @param array $dumpOptions
     * @return ServiceCompilerOptions
     */
    private function setDumpOptions(array $dumpOptions): ServiceCompilerOptions
    {
        $this->dumpOptions = $dumpOptions;
        return $this;
    }
}