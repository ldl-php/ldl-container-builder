<?php

declare(strict_types=1);

namespace LDL\DependencyInjection\CompilerPass\Compiler\Options;

use LDL\DependencyInjection\Interfaces\OptionsInterface;

class CompilerPassCompilerOptions implements OptionsInterface
{
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

    public static function fromArray(array $options): self
    {
        $instance = new static();
        $defaults = get_object_vars($instance);
        $merge = array_merge($defaults, $options);

        return $instance->setOnBeforeCompile($merge['onBeforeCompile'])
            ->setOnCompile($merge['onCompile'])
            ->setOnAfterCompile($merge['onAfterCompile']);
    }

    public function toArray(bool $useKeys = null): array
    {
        return get_object_vars($this);
    }

    public function getOnBeforeCompile(): ?callable
    {
        return $this->onBeforeCompile;
    }

    public function getOnCompile(): ?callable
    {
        return $this->onCompile;
    }

    public function getOnAfterCompile(): ?callable
    {
        return $this->onAfterCompile;
    }

    /**
     * @param callable $fn
     */
    private function setOnAfterCompile(callable $fn = null): CompilerPassCompilerOptions
    {
        $this->onAfterCompile = $fn;

        return $this;
    }

    /**
     * @param callable $fn
     */
    private function setOnCompile(callable $fn = null): CompilerPassCompilerOptions
    {
        $this->onCompile = $fn;

        return $this;
    }

    private function setOnBeforeCompile(callable $fn = null): CompilerPassCompilerOptions
    {
        $this->onBeforeCompile = $fn;

        return $this;
    }
}
