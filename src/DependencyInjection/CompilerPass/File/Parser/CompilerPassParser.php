<?php declare(strict_types=1);

namespace LDL\DependencyInjection\CompilerPass\Parser;

use LDL\DependencyInjection\CompilerPass\LDLCompilerPassInterface;
use LDL\DependencyInjection\CompilerPass\Parser\Validator\CompilerPassValidator;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class CompilerPassParser implements CompilerPassParserInterface
{

    /**
     * @var Options\CompilerPassReaderOptions
     */
    private $options;

    public function __construct(Options\CompilerPassReaderOptions $options = null)
    {
        $this->options = $options ?? Options\CompilerPassReaderOptions::fromArray([]);
    }

    public function parse(ContainerBuilder $container, \SplFileInfo $file) : void
    {
        if(!$this->options->ignoreErrors() && !$file->isReadable()){
            $msg = sprintf(
                'Could not read file "%s", file is not readable! Check filesystem permissions.',
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

            $type = PassConfig::TYPE_BEFORE_OPTIMIZATION;
            $priority = 0;

            if($pass instanceof LDLCompilerPassInterface){
                $type = $pass->getType();
                $priority = $pass->getPriority();
            }

            /**
             * All compilerPasses must extend to LDLAbstractCompilerPass,
             * so getType() and getPriority() they will always be defined
             */
            $container->addCompilerPass($pass, $type, $priority);

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