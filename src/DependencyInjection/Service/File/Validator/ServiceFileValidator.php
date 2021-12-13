<?php

declare(strict_types=1);

namespace LDL\DependencyInjection\Service\File\Validator;

use LDL\DependencyInjection\Service\File\Validator\Exception\ServiceFileValidatorException;
use LDL\DependencyInjection\Service\Helper\ServiceFileHelper;
use LDL\File\Contracts\FileInterface;
use LDL\File\File;
use LDL\Validators\ValidatorInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ServiceFileValidator implements ValidatorInterface
{
    private $builder;

    public function __construct(ContainerBuilder $builder = null)
    {
        $this->builder = $builder ?? new ContainerBuilder();
    }

    public function getDescription(): string
    {
        return 'Validate that a file is a valid container service file';
    }

    public function validate($value): void
    {
        $this->assertTrue($value);
    }

    public function assertTrue($file): void
    {
        if (!$file instanceof FileInterface) {
            $file = new File($file);
        }

        try {
            $loader = ServiceFileHelper::getLoaderByExtension($file, $this->builder);
        } catch (\Exception $e) {
            throw new ServiceFileValidatorException($e->getMessage(), $e->getCode(), $e);
        }

        try {
            $loader->load($file);
        } catch (\Exception $e) {
            $msg = sprintf(
                'File %s could not be loaded by loader: %s',
                $file->getPath(),
                get_class($loader)
            );

            throw new ServiceFileValidatorException($msg, $e->getCode(), $e);
        }
    }
}
