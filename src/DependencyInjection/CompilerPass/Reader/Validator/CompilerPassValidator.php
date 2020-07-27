<?php

declare(strict_types=1);

namespace LDL\DependencyInjection\CompilerPass\Reader\Validator;

use LDL\FS\Type\AbstractFileType;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class CompilerPassValidator
{
    public static function validate(AbstractFileType $file): ?CompilerPassInterface
    {
        $namespace = '';

        preg_match(
            '/namespace.*/i',
            file_get_contents($file->getRealPath()),
            $namespaces
        );

        $namespaceAmount = count($namespaces);

        if($namespaceAmount > 1){
            $msg = sprintf(
                'Multiple namespaces defined, in file: "%s"',
                $file->getRealPath()
            );

            throw new Exception\MultipleNamespacesDefinedException($msg);
        }

        if($namespaceAmount > 0){
            $namespace = trim(preg_replace('#namespace\s+#','', $namespaces[0]), ' ;');
        }

        preg_match(
            '/class.*implements.*CompilerPassInterface.*/i',
            file_get_contents($file->getRealPath()),
            $classesInFile
        );

        if(empty($classesInFile)){
            $msg = sprintf(
                'Could not find any compiler pass class defined in file: "%s"',
                $file->getRealPath()
            );

            throw new Exception\ClassNotFoundException($msg);
        }

        $amountOfClasses = count($classesInFile);

        if($amountOfClasses > 1){
            $msg = sprintf(
                'You may define only ONE compiler pass per file, %s defined in file: "%s"',
                $amountOfClasses,
                $file->getRealPath()
            );

            throw new Exception\MultipleClassesDefinedException($msg);
        }

        $class = $classesInFile[0];
        $class = preg_replace('#\s+#',' ', $class);
        $class = substr($class, strpos($class, ' ')+1);
        $class = substr($class, 0,strpos($class, ' '));

        if('' !== $namespace){
            $class = sprintf('%s\\%s', $namespace,$class);
        }

        if(!class_exists($class)) {
            require_once $file->getRealPath();
        }

        var_dump($class);
        /**
         * @var CompilerPassInterface $passInstance
         */
        $passInstance = new $class();

        if(!($passInstance instanceof CompilerPassInterface)){
            $msg = sprintf(
                'Compiler pass does not implement the correct compiler pass interface (%s), at file: %s',
                CompilerPassInterface::class,
                $file->getRealPath()
            );
            throw new Exception\ImplementsException($msg);
        }

        return $passInstance;
    }
}