<?php

declare(strict_types=1);

namespace LDL\DependencyInjection\CompilerPass\Reader;

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

            /**
             * All compilerPasses must extend to LDLAbstractCompilerPass,
             * so getType() and getPriority() they will always be defined
             */
            $container->addCompilerPass($pass, $pass->getType(), $pass->getPriority());

        } catch(\Exception $e){

            if($this->options->ignoreErrors()){
                return;
            }

            throw new Exception\ValidatorException($e->getMessage());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions(): Options\CompilerPassReaderOptions
    {
        return $this->options;
    }
}