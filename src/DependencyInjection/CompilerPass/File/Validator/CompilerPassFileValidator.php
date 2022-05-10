<?php

declare(strict_types=1);

namespace LDL\DependencyInjection\CompilerPass\File\Validator;

use LDL\DependencyInjection\CompilerPass\LDLCompilerPassInterface;
use LDL\File\Contracts\FileInterface;
use LDL\File\File;
use LDL\Framework\Base\Exception\InvalidArgumentException;
use LDL\Framework\Base\Exception\LDLException;
use LDL\Framework\Helper\ClassHelper;
use LDL\Framework\Helper\ReflectionHelper;
use LDL\Validators\ValidatorInterface;

class CompilerPassFileValidator implements ValidatorInterface
{
    public function getDescription(): string
    {
        return 'Validate that a given file is a valid compiler pass';
    }

    public function validate($file): void
    {
        $this->assertTrue($file);
    }

    public function assertTrue($file): void
    {
        if (!$file instanceof FileInterface) {
            $file = new File((string) $file);
        }

        $data = ReflectionHelper::fromFile($file->getPath());

        $foundClass = false;
        foreach ($data as $namespace => $types) {
            if (0 === count($types['class'])) {
                continue;
            }

            $foundClass = true;

            foreach ($types['class'] as $class) {
                $class = sprintf('%s\\%s', $namespace, $class);

                if (!class_exists($class)) {
                    require_once $file->getPath();
                }

                try {
                    ClassHelper::mustHaveInterface($class, LDLCompilerPassInterface::class);
                } catch (LDLException $e) {
                    $msg = sprintf('In file: "%s", %s', $file->getPath(), $e->getMessage());
                    throw new Exception\CompilerPassValidatorException($msg);
                }
            }
        }

        if (!$foundClass) {
            throw new InvalidArgumentException(sprintf('Found no compiler passes on file: %s', $file->getPath()));
        }
    }
}
