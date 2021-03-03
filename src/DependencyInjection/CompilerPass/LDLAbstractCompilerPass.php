<?php declare(strict_types=1);

namespace LDL\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\ContainerBuilder;

abstract class LDLAbstractCompilerPass implements LDLCompilerPassInterface
{
    private static $defaultPassType;

    private static $defaultPriority;

    final public function __construct()
    {

    }

    private static function setDefaultArguments() : void
    {
        if(null !== self::$defaultPassType){
            return;
        }

        try{

            $rm = new \ReflectionMethod(ContainerBuilder::class, 'addCompilerPass');

        }catch(\ReflectionException $e){

            $msg = 'Container builder class does not has any method named addCompilerPass, this is a critical error, please report the error in github by creating a new issue';
            throw new \RuntimeException($msg);

        }

        self::$defaultPassType = $rm->getParameters()[1]->getDefaultValue();
        self::$defaultPriority = $rm->getParameters()[2]->getDefaultValue();
    }

    public function getPriority() : int
    {
        self::setDefaultArguments();
        return self::$defaultPriority;
    }

    public function getType() : string
    {
        self::setDefaultArguments();
        return self::$defaultPassType;
    }
}