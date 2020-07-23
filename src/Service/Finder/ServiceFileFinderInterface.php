<?php

namespace LDL\Service\Finder;

use LDL\FS\Type\Types\Generic\Collection\GenericFileCollection;

interface ServiceFileFinderInterface
{
    /**
     * @param Options\ServiceFileFinderOptions $options
     * @return GenericFileCollection
     * @throws Exception\NoFilesFoundException
     */
    public function find(Options\ServiceFileFinderOptions $options=null) : GenericFileCollection;
}