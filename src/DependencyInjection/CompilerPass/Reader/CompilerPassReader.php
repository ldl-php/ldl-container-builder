<?php

namespace LDL\DependencyInjection\CompilerPass\Reader;

use LDL\DependencyInjection\CompilerPass\LDLCompilerPassInterface;
use LDL\DependencyInjection\CompilerPass\Reader\Validator\CompilerPassValidator;
use LDL\FS\Type\AbstractFileType;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class CompilerPassReader implements CompilerPassReaderInterface
{

    /**
     * @var Options\CompilerPassReaderOptions
     */
    private $options;

    public function __construct(Options\CompilerPassReaderOptions $options = null)
    {
        $this->options = $options ?? Options\CompilerPassReaderOptions::fromArray([]);
    }

    public function read(ContainerBuilder $container, AbstractFileType $file) : void
    {
        if(!$this->options->ignoreErrors() && !$file->isReadable()){
            $msg = sprintf(
                'Could not read file "%s", file is not readable',
                $file->getRealPath()
            );

            throw new Exception\PermissionException($msg);
        }

        try{

            $pass = CompilerPassValidator::validate($file);

            if(null === $pass){
                return;
            }

            $passClass = get_class($pass);

            $passes = $container->getCompilerPassConfig()->getPasses();

            foreach($passes as $loadedPass) {
                if($passClass === get_class($loadedPass)){
                    return;
                }
            }

            if($pass instanceof LDLCompilerPassInterface) {
                $container->addCompilerPass($pass, $pass->getType(), $pass->getPriority());
            }else{
                $container->addCompilerPass($pass);
            }

        } catch(\Exception $e){

            if($this->options->ignoreErrors()){
                return;
            }

            throw new Exception\ValidatorException($e->getMessage());
        }

    }
}