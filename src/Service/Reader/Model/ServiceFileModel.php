<?php

namespace LDL\Service\Reader\Model;

class ServiceFileModel implements ServiceFileModelInterface
{
    /**
     * @var array
     */
    private $parameters;

    /**
     * @var array
     */
    private $services;

    public static function fromArray(array $options)
    {
        $obj = new static;

        return $obj->setParameters($options['parameters'])
            ->setServices($options['services']);
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @param array $parameters
     * @return ServiceFileModel
     */
    private function setParameters(array $parameters): ServiceFileModelInterface
    {
        $this->parameters = $parameters;
        return $this;
    }

    /**
     * @return array
     */
    public function getServices(): array
    {
        return $this->services;
    }

    /**
     * @param array $services
     * @return ServiceFileModel
     */
    private function setServices(array $services): ServiceFileModelInterface
    {
        $this->services = $services;
        return $this;
    }
}