<?php

namespace LDL\Service\Compiler\Options;

class ServiceCompilerOptions
{
    /**
     * @var bool
     */
    private $allowVariableOverwrite = false;

    /**
     * @var bool
     */
    private $ignoreSyntaxErrors = false;

    /**
     * @var int
     */
    private $prefixDepth=0;

    /**
     * @var bool
     */
    private $convertToUpperCase=true;

    /**
     * @var bool
     */
    private $commentsEnabled = true;

    /**
     * @var bool
     */
    private $removeComments = false;

    /**
     * @var callable
     */
    private $onBeforeCompile;

    /**
     * @var callable
     */
    private $onCompile;

    /**
     * @var callable
     */
    private $onAfterCompile;

    private function __construct()
    {
    }

    public static function fromArray(array $options) : self
    {
        $instance = new static();
        $defaults = get_object_vars($instance);

        foreach($options as $opt=>$value){
            if(array_key_exists($opt, $defaults)) {
                continue;
            }
            $msg = sprintf(
                'Unknown option: "%s", valid options are: %s',
                $opt,
                implode(', ', array_keys($defaults))
            );

            throw new Exception\UnknownOptionException($msg);
        }

        $merge = array_merge($defaults, $options);

        return $instance->setAllowVariableOverwrite($merge['allowVariableOverwrite'])
            ->SetIgnoreSyntaxErrors($merge['ignoreSyntaxErrors'])
            ->setPrefixDepth($merge['prefixDepth'])
            ->setConvertToUpperCase($merge['convertToUpperCase'])
            ->setCommentsEnabled($merge['commentsEnabled'])
            ->setRemoveComments($merge['removeComments'])
            ->setOnBeforeCompile($merge['onBeforeCompile'])
            ->setOnCompile($merge['onCompile'])
            ->setOnAfterCompile($merge['onAfterCompile']);
    }

    /**
     * @return callable|null
     */
    public function getOnBeforeCompile() : ?callable
    {
        return $this->onBeforeCompile;
    }

    /**
     * @return callable|null
     */
    public function getOnCompile() : ?callable
    {
        return $this->onCompile;
    }

    /**
     * @return callable|null
     */
    public function getOnAfterCompile() : ?callable
    {
        return $this->onAfterCompile;
    }

    /**
     * @param bool $allowVariableOverwrite
     * @return ServiceCompilerOptions
     */
    public function setAllowVariableOverwrite(bool $allowVariableOverwrite): ServiceCompilerOptions
    {
        $this->allowVariableOverwrite = $allowVariableOverwrite;
        return $this;
    }

    /**
     * @return bool
     */
    public function convertToUpperCase(): bool
    {
        return $this->convertToUpperCase;
    }

    /**
     * @return bool
     */
    public function allowVariableOverwrite(): bool
    {
        return $this->allowVariableOverwrite;
    }

    /**
     * @return bool
     */
    public function ignoreSyntaxErrors(): bool
    {
        return $this->ignoreSyntaxErrors;
    }
    /**
     * @return int
     */
    public function getPrefixDepth(): int
    {
        return $this->prefixDepth;
    }

    /**
     * @return bool
     */
    public function commentsEnabled(): bool
    {
        return $this->commentsEnabled;
    }

    /**
     * @param int $prefixDepth
     * @return ServiceCompilerOptions
     */
    private function setPrefixDepth(int $prefixDepth): ServiceCompilerOptions
    {
        $this->prefixDepth = $prefixDepth;
        return $this;
    }

    /**
     * @param bool $convertToUpperCase
     * @return ServiceCompilerOptions
     */
    private function setConvertToUpperCase(bool $convertToUpperCase): ServiceCompilerOptions
    {
        $this->convertToUpperCase = $convertToUpperCase;
        return $this;
    }

    /**
     * @param bool $enabled
     * @return ServiceCompilerOptions
     */
    private function setCommentsEnabled(bool $enabled): ServiceCompilerOptions
    {
        $this->commentsEnabled = $enabled;

        return $this;
    }

    /**
     * @return bool
     */
    public function removeComments(): bool
    {
        return $this->removeComments;
    }

    /**
     * @param bool $removeComments
     * @return ServiceCompilerOptions
     */
    private function setRemoveComments(bool $removeComments): ServiceCompilerOptions
    {
        $this->removeComments = $removeComments;
        return $this;
    }

    /**
     * @param bool $ignoreSyntaxErrors
     * @return ServiceCompilerOptions
     */
    private function setIgnoreSyntaxErrors(bool $ignoreSyntaxErrors): ServiceCompilerOptions
    {
        $this->ignoreSyntaxErrors = $ignoreSyntaxErrors;
        return $this;
    }

    /**
     * @param callable $fn
     * @return ServiceCompilerOptions
     */
    private function setOnAfterCompile(callable $fn=null) : ServiceCompilerOptions
    {
        $this->onAfterCompile = $fn;
        return $this;
    }

    /**
     * @param callable $fn
     * @return ServiceCompilerOptions
     */
    private function setOnCompile(callable $fn=null) : ServiceCompilerOptions
    {
        $this->onCompile = $fn;
        return $this;
    }

    /**
     * @param callable|null $fn
     * @return ServiceCompilerOptions
     */
    private function setOnBeforeCompile(callable $fn=null) : ServiceCompilerOptions
    {
        $this->onBeforeCompile = $fn;
        return $this;
    }

}