<?php declare(strict_types=1);

namespace LDL\DependencyInjection\Service\File\Parser\Options;

use LDL\DependencyInjection\Interfaces\OptionsInterface;

class ServiceFileParserOptions implements OptionsInterface
{
    /**
     * @var bool
     */
    private $ignoreErrors = false;

    private function __construct()
    {
    }

    public static function fromArray(array $options)
    {
        $obj = new static;
        $merge = array_merge(get_object_vars($obj), $options);

        return $obj->setIgnoreErrors($merge['ignoreErrors']);
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

    private function setIgnoreErrors(bool $value) : ServiceFileParserOptions
    {
        $this->ignoreErrors = $value;
        return $this;
    }

    public function ignoreErrors() : bool
    {
        return $this->ignoreErrors;
    }

}
