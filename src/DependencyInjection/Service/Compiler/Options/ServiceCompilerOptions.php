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

    public function __construct(
        CallableCollectionInterface $onBeforeCompile = null,
        CallableCollectionInterface $onCompile = null,
        CallableCollectionInterface $onCompileError = null,
        CallableCollectionInterface $onAfterCompile = null
    ) {
        $this->onBeforeCompile = $onBeforeCompile;
        $this->onCompile = $onCompile;
        $this->onCompileError = $onCompileError;
        $this->onAfterCompile = $onAfterCompile;
    }

    public static function fromArray(array $options): self
    {
        $merge = array_merge(get_class_vars(__CLASS__), $options);

        return new self(
            $merge['onBeforeCompile'],
            $merge['onCompile'],
            $merge['onCompileError'],
            $merge['onAfterCompile']
        );
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
}
