<?php

namespace LDL\Service\Reader\Options;

use LDL\FS\Type\AbstractFileType;

class ServiceReaderOptions
{
    /**
     * @var AbstractFileType
     */
    private $file;

    public static function fromArray(array $options)
    {
        $obj = new static;

        return $obj->setFile($options['file']);
    }

    public function getFile() : AbstractFileType
    {
        return $this->file;
    }

    private function setFile(AbstractFileType $file) : ServiceReaderOptions
    {
        $this->file = $file;
        return $this;
    }
}
