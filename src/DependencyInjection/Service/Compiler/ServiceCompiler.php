<?php

declare(strict_types=1);

namespace LDL\DependencyInjection\Service\Compiler;

use LDL\DependencyInjection\Service\Compiler\Directive\Collection\ServiceCompilerDirectiveCollection;
use LDL\DependencyInjection\Service\File\ServiceFileCollection;
use LDL\DependencyInjection\Service\Helper\ServiceFileHelper;
use LDL\File\Contracts\FileInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ServiceCompiler implements ServiceCompilerInterface
{
    /**
     * @var Options\ServiceCompilerOptions
     */
    private $options;

    /**
     * @var ServiceCompilerDirectiveCollection
     */
    private $directives;

    public function __construct(
        ServiceCompilerDirectiveCollection $directives = null,
        Options\ServiceCompilerOptions $options = null
    ) {
        $this->options = $options;
        $this->directives = $directives;
    }

    public function compile(
        ContainerBuilder $builder,
        ServiceFileCollection $serviceFiles
    ): void {
        $definedServices = [];

        /**
         * @var FileInterface $file
         */
        foreach ($serviceFiles as $file) {
            if (null !== $this->options && $this->options->getOnBeforeCompile()) {
                $this->options->getOnBeforeCompile()->call($builder, $serviceFiles);
            }

            $loader = ServiceFileHelper::getLoaderByExtension($file, $builder);

            if (null !== $this->directives) {
                $this->directives->compile($builder, $file, $definedServices);

                $definedServices[$file->getPath()] = ServiceFileHelper::getDefinedServicesInFile($file)
                    ->toPrimitiveArray(true);
            }

            try {
                $loader->load($file);
            } catch (\Exception $e) {
                if (null !== $this->options && $this->options->getOnCompileError()) {
                    $this->options->getOnCompileError()->call($builder, $loader, $file);
                }
            }

            if (null !== $this->options && $this->options->getOnCompile()) {
                $this->options->getOnCompile()->call($builder, $file, $loader);
            }
        }

        if (null !== $this->options && $this->options->getOnAfterCompile()) {
            $this->options->getOnAfterCompile()->call($builder, $serviceFiles);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions(): Options\ServiceCompilerOptions
    {
        return $this->options;
    }
}
