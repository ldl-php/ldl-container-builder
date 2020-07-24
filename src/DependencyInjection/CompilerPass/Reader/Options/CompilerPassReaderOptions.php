<?php

namespace LDL\DependencyInjection\CompilerPass\Reader\Options;

class CompilerPassReaderOptions
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

    private function setIgnoreErrors(bool $value) : CompilerPassReaderOptions
    {
        $this->ignoreErrors = $value;
        return $this;
    }

    public function ignoreErrors() : bool
    {
        return $this->ignoreErrors;
    }

}
