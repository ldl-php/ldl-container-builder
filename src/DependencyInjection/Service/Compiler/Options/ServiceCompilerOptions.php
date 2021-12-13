<?php

declare(strict_types=1);

namespace LDL\DependencyInjection\Service\Compiler\Options;

use LDL\DependencyInjection\Interfaces\OptionsInterface;
use LDL\Framework\Base\Collection\CallableCollectionInterface;

class ServiceCompilerOptions implements OptionsInterface
{
    /**
     * @var ?CallableCollectionInterface
     */
    private $onBeforeCompile;

    /**
     * @var ?CallableCollectionInterface
     */
    private $onCompile;

    /**
     * @var ?CallableCollectionInterface
     */
    private $onCompileError;

    /**
     * @var ?CallableCollectionInterface
     */
    private $onAfterCompile;

    public static function fromArray(array $options): self
    {
        $instance = new static();
        $defaults = get_object_vars($instance);
        $merge = array_merge($defaults, $options);

        return $instance->setOnBeforeCompile($merge['onBeforeCompile'])
            ->setOnCompile($merge['onCompile'])
            ->setOnCompileError($merge['onCompileError'])
            ->setOnAfterCompile($merge['onAfterCompile']);
    }

    public function toArray(bool $useKeys = null): array
    {
        return get_object_vars($this);
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function getOnBeforeCompile(): ?CallableCollectionInterface
    {
        return $this->onBeforeCompile;
    }

    public function getOnCompile(): ?CallableCollectionInterface
    {
        return $this->onCompile;
    }

    public function getOnAfterCompile(): ?CallableCollectionInterface
    {
        return $this->onAfterCompile;
    }

    public function getOnCompileError(): ?CallableCollectionInterface
    {
        return $this->onCompileError;
    }

    //<editor-fold desc="Private methods">
    private function setOnAfterCompile(CallableCollectionInterface $fn = null): ServiceCompilerOptions
    {
        $this->onAfterCompile = $fn;

        return $this;
    }

    private function setOnCompile(CallableCollectionInterface $fn = null): ServiceCompilerOptions
    {
        $this->onCompile = $fn;

        return $this;
    }

    private function setOnCompileError(CallableCollectionInterface $fn = null): ServiceCompilerOptions
    {
        $this->onCompileError = $fn;

        return $this;
    }

    private function setOnBeforeCompile(CallableCollectionInterface $fn = null): ServiceCompilerOptions
    {
        $this->onBeforeCompile = $fn;

        return $this;
    }
    //</editor-fold>
}
