<?php

namespace LDL\DependencyInjection\Service\Reader\Options;

class ServiceReaderOptions
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

    private function setIgnoreErrors(bool $value) : ServiceReaderOptions
    {
        $this->ignoreErrors = $value;
        return $this;
    }

    public function ignoreErrors() : bool
    {
        return $this->ignoreErrors;
    }

}
