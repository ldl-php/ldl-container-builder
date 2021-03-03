<?php declare(strict_types=1);

namespace LDL\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface as SymfonyCompilerPass;

interface LDLCompilerPassInterface extends SymfonyCompilerPass
{

    /**
     * @return int
     */
    public function getPriority() : int;

    /**
     * @return string
     */
    public function getType() : string;

}