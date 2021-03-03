<?php declare(strict_types=1);

namespace LDL\DependencyInjection\Service\Compiler\Options;

use LDL\DependencyInjection\Interfaces\OptionsInterface;

class ServiceCompilerOptions implements OptionsInterface
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

    public static function fromArray(array $options) : self
    {
        $instance = new static();
        $defaults = get_object_vars($instance);
        $merge = array_merge($defaults, $options);

        return $instance->setOnBeforeCompile($merge['onBeforeCompile'])
            ->setOnCompile($merge['onCompile'])
            ->setOnAfterCompile($merge['onAfterCompile']);
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
}